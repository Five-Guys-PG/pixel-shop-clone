import json
import os
import random
from pathlib import Path
from typing import Any, Coroutine

import httpx
from dotenv import load_dotenv
from jinja2 import Environment, FileSystemLoader
from tqdm import tqdm

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
PRODUCT_API_ENDPOINT = f"{PRESTA_URL}/api/products"


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


class Product(PrestaItem):
    def __init__(self, product_json: dict, category: Category):
        super().__init__()
        self.category = category

        self.name = product_json["name"]
        self.price = float(product_json["price"].split("\xa0")[0].replace(",", "."))
        self.description = product_json.get("description", "")
        self.ean13 = "".join([str(random.randint(0, 9)) for _ in range(13)])

    def to_xml(self):
        template = PrestaItem.jinja_env.get_template("product_template.xml")
        return template.render(
            ean13=self.ean13,
            category_id=self.category.id,
            name=self.name,
            description=self.description,
            price=self.price,
        )

    def set_id_from_response(self, response: dict):
        try:
            self.id = response["product"]["id"]
        except KeyError:
            pass


def flatten_list(nested_list: list) -> list:
    return [item for sublist in nested_list for item in sublist]


class Uploader:
    def __init__(self, api_key: str) -> None:
        self._auth = httpx.BasicAuth(username=api_key, password="")
        self._client = httpx.Client(auth=self._auth)
        self._data = {}

    def _add_io_format_json(self, url: str) -> str:
        return url + "&io_format=JSON"

    def load_data_from_file(self, filename: str | os.PathLike) -> None:
        with open(filename, "r") as json_file:
            self._data = json.load(json_file)

    def create_product(self, product_json: dict, category: Category):
        product = Product(product_json, category)
        product_xml = product.to_xml()
        response = self._client.post(
            self._add_io_format_json(PRODUCT_API_ENDPOINT),
            content=product_xml.encode("utf-8"),
        )
        product.set_id_from_response(response.json())
        return product

    def create_subcategory(
        self, subcategory_json: dict, parent: Category
    ):
        subcategory = Subcategory(subcategory_json["subcategory"], parent)
        subcategory_xml = subcategory.to_xml()
        response = self._client.post(
            self._add_io_format_json(CATEGORY_API_ENDPOINT),
            content=subcategory_xml.encode("utf-8"),
        )
        subcategory.set_id_from_response(response.json())
        if "products" not in subcategory_json:
            return
        print('Adding products to ' + subcategory.name)
        for product in tqdm(subcategory_json["products"]):
            self.create_product(product, subcategory)

    def create_category(self, category_json: dict):
        category = Category(category_json["category"])
        category_xml = category.to_xml()
        response = self._client.post(
            self._add_io_format_json(CATEGORY_API_ENDPOINT),
            content=category_xml.encode("utf-8"),
        )
        category.set_id_from_response(response.json())

        for subcategory in category_json["products"]:
            self.create_subcategory(subcategory, category)

    def run_all(self) -> None:
        for category in self._data:
            self.create_category(category) 

if __name__ == "__main__":
    uploader = Uploader(api_key=API_KEY)
    uploader.load_data_from_file(DATA_FILE)
    uploader.run_all()
