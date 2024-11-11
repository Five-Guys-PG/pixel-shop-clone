from uploader import API_KEY, CATEGORY_API_ENDPOINT
import httpx


client = httpx.Client(auth=httpx.BasicAuth(username=API_KEY, password=""))

def remove_all_categories():
   response = client.get(CATEGORY_API_ENDPOINT + "?io_format=JSON") 
   data = response.json()
   if "categories" in data:
       for category in data["categories"]:
           client.delete(CATEGORY_API_ENDPOINT + f"/{category['id']}")

if __name__ == "__main__":
    remove_all_categories()
