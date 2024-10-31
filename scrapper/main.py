from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import NoSuchElementException, TimeoutException
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.firefox.service import Service
from selenium.webdriver.firefox.options import Options
from selenium.webdriver.common.by import By
from selenium import webdriver
from bs4 import BeautifulSoup
import time
import requests
import json
import os


firefox_options = Options()
# firefox_options.add_argument("--headless")
firefox_service = Service("/snap/bin/geckodriver")

# Initialization firefox browser
driver = webdriver.Firefox(service=firefox_service, options=firefox_options)

site_url = "https://pixel-shop.pl"


def remove_preloader():
    driver.execute_script("""
        var preloader = document.getElementById('pixel-preloader');
        if (preloader) {
            preloader.style.display = 'none';
        }
    """)


def click_element(by, value, timeout=10):
    try:
        element = WebDriverWait(driver, timeout).until(
            EC.element_to_be_clickable((by, value))
        )
        element.click()
    except (NoSuchElementException, TimeoutException):
        print(f"Element {value} not found.")


def setup_page_for_scraping_using_selenium(site_url):
    # Opening of site
    driver.get(site_url)

    # Removing the preloader
    remove_preloader()

    try:
        WebDriverWait(driver, 10).until(
            EC.invisibility_of_element_located((By.ID, "pixel-preloader"))
        )
    except TimeoutException:
        print("Preloader did not disappear in the designated time.")

    time.sleep(2)

    # Closing the cookies module
    click_element(By.CLASS_NAME, "js__accept-all-consents")


def scrape_delivery_info_and_recommendations(full_url):
    delivery_data = []
    recommended_products = []

    driver.get(full_url)

    remove_preloader()

    try:
        click_element(By.CLASS_NAME, "box_productdeliveries")

        driver.implicitly_wait(2)

        delivery_rows = driver.find_elements(By.CLASS_NAME, "row-delivery")

        for row in delivery_rows:
            delivery_item = {}

            try:
                image_element = row.find_element(By.TAG_NAME, "img")
                image_url = image_element.get_attribute("src")
            except NoSuchElementException:
                image_url = None

            columns = row.find_elements(By.CLASS_NAME, "col-delivery")
            if len(columns) == 2:
                description = columns[0].text.strip()
                price = columns[1].text.strip()

                if not description and image_url:
                    delivery_item["description"] = image_url.split("/")[-1]
                else:
                    delivery_item["description"] = description

                delivery_item["price"] = price
            elif len(columns) == 1:
                description = columns[0].text.strip()

                delivery_item["description"] = description
                delivery_item["price"] = None

            delivery_data.append(delivery_item)
    except (NoSuchElementException, TimeoutException):
        pass

    try:
        click_element(By.CLASS_NAME, "box_productrelated")

        driver.implicitly_wait(2)

        recommended_items = driver.find_elements(By.CLASS_NAME, "product")
        for item in recommended_items:
            try:
                link_element = item.find_element(By.TAG_NAME, "a")
                item_name = link_element.get_attribute("title").strip()
                details_link = link_element.get_attribute("href")
                recommended_products.append(
                    {
                        "name": item_name,
                        "link": details_link,
                    }
                )
            except NoSuchElementException:
                pass
    except (NoSuchElementException, TimeoutException):
        pass
    return delivery_data, recommended_products


def scrape_product_details(product_url):
    full_url = site_url + product_url
    response = requests.get(full_url)

    if response.status_code == 200:
        product_soup = BeautifulSoup(response.content, "html.parser")
        availability_tag = product_soup.find("div", class_="availability")
        is_available = (
            availability_tag.find("span", class_="second").text.strip()
            if availability_tag
            else None
        )

        pixel_coins = product_soup.find("span", class_="points")
        pixel_coins = pixel_coins.text.strip() if pixel_coins else None

        print(full_url)
        delivery_info, recommended_products = scrape_delivery_info_and_recommendations(
            full_url
        )

        description_div = product_soup.find(
            "div", class_="resetcss", itemprop="description"
        )
        description = (
            description_div.get_text(separator=" ", strip=True)
            if description_div
            else None
        )

        enlarged_image_tag = product_soup.find("a", class_="js__gallery-anchor-image")
        enlarged_image_link = (
            site_url + enlarged_image_tag.get("href") if enlarged_image_tag else None
        )

        image_tags = description_div.find_all("img") if description_div else []
        image_links = [
            site_url + img.get("src") for img in image_tags if img.get("src")
        ]

        return {
            "is_available": is_available,
            "delivery_info": delivery_info,
            "description": description,
            "images_in_description": image_links,
            "recommended_products": recommended_products,
            "pixel-coins": pixel_coins,
            "second image": enlarged_image_link,
        }
    else:
        print(f"Failed to retrieve {full_url}. Status code: {response.status_code}")
        return None


def extract_product_info(product):
    item_name = product.find("span", class_="productname").text.strip()

    price_tag = product.find("div", class_="price f-row").find("em")
    item_price = price_tag.text.strip() if price_tag else None

    previous_price_tag = product.find("del", class_="price__inactive")
    previous_price = previous_price_tag.text.strip() if previous_price_tag else None

    img_tag = product.find("img")
    first_image = (
        site_url + img_tag.get("data-src")
        if img_tag and img_tag.get("data-src")
        else None
    )

    details_link = product.find("a").get("href")

    detailed_info = scrape_product_details(details_link)

    return {
        "name": item_name,
        "price": item_price,
        "previous_price": previous_price,
        "first_image": first_image,
        "details": detailed_info,
    }


def scrape_subcategory_page(category_url):
    full_url = category_url
    response = requests.get(full_url)
    scraped_data = []

    while full_url:
        response = requests.get(full_url)

        if response.status_code == 200:
            category_soup = BeautifulSoup(response.content, "html.parser")

            items = category_soup.find_all(class_="product")

            for item in items:
                item_info = extract_product_info(item)
                scraped_data.append(item_info)

            next_page_link = category_soup.find("link", rel="next")
            if next_page_link and "href" in next_page_link.attrs:
                full_url = next_page_link["href"]
            else:
                full_url = None
        else:
            print(f"Failed to retrieve {full_url}. Status code: {response.status_code}")
            break

    return scraped_data


def append_data_to_json(data, filename):
    # Check for existence
    if os.path.exists(filename):
        with open(filename, "r", encoding="utf-8") as f:
            try:
                existing_data = json.load(f)
            except json.JSONDecodeError:
                existing_data = []  # error
    else:
        existing_data = []  # not existing

    # Appending
    existing_data.extend(data)

    # Save appended data
    with open(filename, "w", encoding="utf-8") as f:
        json.dump(existing_data, f, ensure_ascii=False, indent=4)


def scrape_categories(category_url, category_name):
    """Scrapes categories under a given url."""
    category_data = []
    full_url = site_url + category_url

    driver.get(full_url)

    driver.implicitly_wait(2)

    categorylist = driver.find_element(By.ID, "pixel_subcategories")
    elements = categorylist.find_elements(By.TAG_NAME, "a")

    for element in elements:
        link_text = element.text
        link_href = element.get_attribute("href")
        category_data.append({"category": link_text, "url": link_href})

    return category_data


def scrape_subcategories(category_object):
    """Scrapes subcategories under a given category."""
    category_items_data = []
    subcategory_data = []

    category_url = category_object.get("url")

    if not category_url:
        print("Category URL is missing")
        return []

    full_url = category_url

    driver.get(full_url)

    driver.implicitly_wait(2)

    categorylist = driver.find_element(By.ID, "pixel_subcategories")
    elements = categorylist.find_elements(By.TAG_NAME, "a")

    if elements == []:
        print(
            f"No subcategories for {category_object['category']}, scraping products directly."
        )
        category_items_data.append(
            {
                "subcategory": category_object["category"],
                "products": scrape_subcategory_page(category_url),
            }
        )

        return category_items_data

    for element in elements:
        link_text = element.text
        link_href = element.get_attribute("href")
        subcategory_data.append({"subcategory": link_text, "link": (link_href)})

    for subcategory in subcategory_data:
        print(f"Scraping category: {subcategory['subcategory']}")
        print(f"Link: {subcategory['link']}")

        # Check if this subcategory contains further nested subcategories
        driver.get(subcategory["link"])
        driver.implicitly_wait(2)

        try:
            nested_category_list = driver.find_element(By.ID, "pixel_subcategories")
            nested_elements = nested_category_list.find_elements(By.TAG_NAME, "a")

            # If nested subcategories are found, recurse into them
            nested_subcategories = []
            for nested_element in nested_elements:
                nested_text = nested_element.text
                nested_link = nested_element.get_attribute("href")
                nested_subcategories.append(
                    {"subcategory": nested_text, "link": nested_link}
                )

            if nested_subcategories:
                # Recursively scrape nested subcategories
                nested_category_data = scrape_subcategories(
                    {
                        "subcategory": subcategory["subcategory"],
                        "url": subcategory["link"],
                    }
                )
                category_items_data.append(
                    {
                        "subcategory": subcategory["subcategory"],
                        "nested_subcategories": nested_category_data,
                    }
                )
            else:
                # No further nesting; directly scrape products
                category_items_data.append(
                    {
                        "subcategory": subcategory["subcategory"],
                        "products": scrape_subcategory_page(subcategory["link"]),
                    }
                )

        except NoSuchElementException:
            # No further nesting; directly scrape products
            category_items_data.append(
                {
                    "subcategory": subcategory["subcategory"],
                    "products": scrape_subcategory_page(subcategory["link"]),
                }
            )

    return category_items_data


# Send a request to the main page
response = requests.get(site_url)
setup_page_for_scraping_using_selenium(site_url)
time.sleep(3)

if response.status_code == 200:
    all_scraped_data = []
    # Parse the main page content
    soup = BeautifulSoup(response.content, "html.parser")

    menu = soup.find_all("li", class_="parent")
    category_element = soup.find(id="hcategory_100")

    if category_element:
        link_element = category_element.find("a")
        if link_element:
            category_name = (
                link_element.find("span").text.strip()
                if link_element.find("span")
                else link_element.get("title", "").strip()
            )
            category_href = link_element.get("href")
    categories_href = scrape_categories(category_href, category_name)

    for category in categories_href:
        print(category)

        category_data = scrape_subcategories(category)

        scraped_data = [{"category": category["category"], "products": category_data}]

        print(f"Saved {len(category_data)} products to JSON file.")
        # Save the current category data to JSON
        append_data_to_json(scraped_data[-1:], "scraped_data_category.json")
        print("-" * 40)

    driver.quit()

else:
    print(f"Failed to retrieve the main page. Status code: {response.status_code}")
