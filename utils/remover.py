import httpx
from tqdm import tqdm

from uploader import API_KEY, CATEGORY_API_ENDPOINT, PRODUCT_API_ENDPOINT

client = httpx.Client(auth=httpx.BasicAuth(username=API_KEY, password=""))


def remove_all_categories():
    response = client.get(CATEGORY_API_ENDPOINT + "?io_format=JSON")
    data = response.json()
    if "categories" in data:
        print("Removing categories...")
        for category in tqdm(data["categories"]):
            client.delete(CATEGORY_API_ENDPOINT + f"/{category['id']}")


def remove_all_products():
    response = client.get(PRODUCT_API_ENDPOINT + "?io_format=JSON")
    data = response.json()
    if "products" in data:
        print("Removing products...")
        for product in tqdm(data["products"]):
            client.delete(PRODUCT_API_ENDPOINT + f"/{product['id']}")


if __name__ == "__main__":
    remove_all_categories()
    remove_all_products()
