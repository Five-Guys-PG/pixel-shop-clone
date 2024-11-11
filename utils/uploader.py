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


class Uploader:
    def __init__(self, api_key: str) -> None:
        self._auth = httpx.BasicAuth(username=api_key, password="")

        self._data = {}

    def _add_io_format_json(self, url: str) -> str:
        return url + "&io_format=JSON"

    def load_data_from_file(self, filename: str | os.PathLike) -> None:
        with open(filename, "r") as json_file:
            self._data = json.load(json_file)

    async def create_category(self, category_json: dict) -> Category:
        async with httpx.AsyncClient(auth=self._auth) as client:
            category = Category(category_json["category"])
            category_xml = category.to_xml()
            response = await client.post(
                self._add_io_format_json(CATEGORY_API_ENDPOINT),
                content=category_xml.encode("utf-8"),
            )
            category.set_id_from_response(response.json())
            return category

    async def create_categories(self):
        debug_print("Starting uploading categories")
        create_category_tasks = [
            self.create_category(category) for category in self._data
        ]
        debug_print(f"Found {len(create_category_tasks)} categories")
        await asyncio.gather(*create_category_tasks)

    async def run_all(self) -> None:
        await self.create_categories()


if __name__ == "__main__":
    uploader = Uploader(api_key=API_KEY)
    uploader.load_data_from_file(DATA_FILE)

    asyncio.run(uploader.run_all())
