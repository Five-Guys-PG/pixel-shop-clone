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
firefox_options.add_argument("--headless")
firefox_service = Service("/snap/bin/geckodriver")

# Initialization firefox browser
driver = webdriver.Firefox(service=firefox_service, options=firefox_options)

site_url = "https://pixel-shop.pl"


def setup_page_for_scraping_using_selenium(site_url):
    # Opening of site
    driver.get(site_url)

    # Removing the preloader
    driver.execute_script("""
        var preloader = document.getElementById('pixel-preloader');
        if (preloader) {
            preloader.style.display = 'none';
        }
    """)

    try:
        WebDriverWait(driver, 10).until(
            EC.invisibility_of_element_located((By.ID, "pixel-preloader"))
        )
    except TimeoutException:
        print("Preloader nie zniknął w wyznaczonym czasie.")

    time.sleep(2)

    # Closing the cookies module
    try:
        accept_cookies_button = WebDriverWait(driver, 10).until(
            EC.element_to_be_clickable((By.CLASS_NAME, "js__accept-all-consents"))
        )
        accept_cookies_button.click()
    except Exception as e:
        print("Modal cookies nie znaleziony lub już zamknięty:", str(e))


def scrape_delivery_info(full_url):
    delivery_data = []

    driver.get(full_url)

    driver.execute_script("""
        var preloader = document.getElementById('pixel-preloader');
        if (preloader) {
            preloader.style.display = 'none';
        }
    """)

    koszty_dostawy_tab = driver.find_element(By.CLASS_NAME, "box_productdeliveries")
    koszty_dostawy_tab.click()

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
    return delivery_data


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
        delivery_info = scrape_delivery_info(full_url)

        # Extract product description
        description_div = product_soup.find(
            "div", class_="resetcss", itemprop="description"
        )
        description = (
            description_div.get_text(separator=" ", strip=True)
            if description_div
            else None
        )

        # Extract enlarge image link
        enlarged_image_tag = product_soup.find("a", class_="js__gallery-anchor-image")
        enlarged_image_link = (
            site_url + enlarged_image_tag.get("href") if enlarged_image_tag else None
        )

        return {
            "is_available": is_available,
            "delivery_info": delivery_info,
            "description": description,
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


def scrape_category_page(category_url):
    full_url = site_url + category_url
    response = requests.get(full_url)
    scraped_data = []

    if response.status_code == 200:
        category_soup = BeautifulSoup(response.content, "html.parser")

        items = category_soup.find_all(class_="product")

        for item in items:
            item_info = extract_product_info(item)
            scraped_data.append(item_info)

        return scraped_data
    else:
        print(f"Failed to retrieve {full_url}. Status code: {response.status_code}")
        return []


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


# Send a request to the main page
response = requests.get(site_url)
setup_page_for_scraping_using_selenium(site_url)
time.sleep(5)

if response.status_code == 200:
    # Parse the main page content
    soup = BeautifulSoup(response.content, "html.parser")

    submenu_items = soup.find_all("div", class_="submenu level1")

    all_scraped_data = []

    for submenu in submenu_items:
        # For each submenu, find the links
        links = submenu.find_all("a")

        for link in links:
            category_name = link.find("span").text.strip()
            category_link = link.get("href")

            print(f"Scraping category: {category_name}")
            print(f"Link: {category_link}")

            # Scrape the individual category page
            category_data = scrape_category_page(category_link)

            all_scraped_data.append(
                {"category": category_name, "products": category_data}
            )

            print(f"Zapisano {len(category_data)} produktów do pliku JSON.")
            # Save the current category data to JSON
            append_data_to_json(all_scraped_data[-1:], "scraped_data.json")
            print("-" * 40)


else:
    print(f"Failed to retrieve the main page. Status code: {response.status_code}")
