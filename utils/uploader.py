import asyncio
import json
import os
from pathlib import Path

import httpx
from jinja2 import Environment, FileSystemLoader
from dotenv import load_dotenv

load_dotenv()


API_KEY = os.environ.get("API_KEY")

if API_KEY is None:
    print("API_KEY env variable is not set")
    exit(1)

PRESTA_URL = (
    os.environ["PRESTA_URL"] if "PRESTA_URL" in os.environ else "http://localhost:8080"
)
DATA_FILE = Path(__file__).parent.parent / "scrapper" / "scraped_data_category.json"
XML_TEMPLATES_DIR = Path(__file__).parent / "xml_templates"

CATEGORY_API_ENDPOINT = f"{PRESTA_URL}/api/categories"


def debug_print(message: str) -> None:
    if os.environ.get("DEBUG"):
        print(message)


class PrestaItem:
    jinja_env = Environment(loader=FileSystemLoader(XML_TEMPLATES_DIR))

    def __init__(self) -> None:
        self.id: int = -1


class Category(PrestaItem):
    def __init__(self, name: str):
        super().__init__()
        self.name = name

    def to_xml(self):
        template = PrestaItem.jinja_env.get_template("category_template.xml")
        return template.render(name=self.name)

    def set_id_from_response(self, response: dict):
        try:
            self.id = response["category"]["id"]
        except KeyError:
            pass

class Subcategory(Category):
    def __init__(self, name: str, parent: Category):
        super().__init__(name)
        self.name = name
        self.parent = parent

    def to_xml(self):
        template = PrestaItem.jinja_env.get_template("subcategory_template.xml")
        return template.render(name=self.name, parent_id=self.parent.id)


def flatten_list(nested_list: list) -> list:
    return [item for sublist in nested_list for item in sublist]

class Uploader:
    def __init__(self, api_key: str) -> None:
        self._auth = httpx.BasicAuth(username=api_key, password="")
        self._client = httpx.AsyncClient(auth=self._auth)
        self._data = {}

    def _add_io_format_json(self, url: str) -> str:
        return url + "&io_format=JSON"

    def load_data_from_file(self, filename: str | os.PathLike) -> None:
        with open(filename, "r") as json_file:
            self._data = json.load(json_file)

    async def create_subcategory(self, subcategory_json: dict, parent: Category) -> Subcategory:
        subcategory = Subcategory(subcategory_json["subcategory"], parent)
        subcategory_xml = subcategory.to_xml()
        response = await self._client.post(
            self._add_io_format_json(CATEGORY_API_ENDPOINT),
            content=subcategory_xml.encode("utf-8"),
        )
        subcategory.set_id_from_response(response.json())
        return subcategory

    async def create_category(self, category_json: dict) -> list[asyncio.Task]:
        category = Category(category_json["category"])
        category_xml = category.to_xml()
        response = await self._client.post(
            self._add_io_format_json(CATEGORY_API_ENDPOINT),
            content=category_xml.encode("utf-8"),
        )
        category.set_id_from_response(response.json())

        create_subcategory_tasks = [
            self.create_subcategory(subcategory, category)
            for subcategory in category_json["products"]
        ]
        return create_subcategory_tasks

    async def run_all(self) -> None:
        create_category_tasks = [
            self.create_category(category) for category in self._data
        ]
        create_subcategory_tasks = flatten_list(await asyncio.gather(*create_category_tasks))
        await asyncio.gather(*create_subcategory_tasks)
        await self._client.aclose()

if __name__ == "__main__":
    uploader = Uploader(api_key=API_KEY)
    uploader.load_data_from_file(DATA_FILE)

    asyncio.run(uploader.run_all())
