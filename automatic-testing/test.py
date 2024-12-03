from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.firefox.service import Service
from selenium.webdriver.common.action_chains import ActionChains
from selenium.webdriver.firefox.options import Options
import time
import random

##############################
firefox_options = Options()
# firefox_options.add_argument("--headless")
firefox_service = Service("/snap/bin/geckodriver")

# Initialization firefox browser
driver = webdriver.Firefox(service=firefox_service, options=firefox_options)

site_url = "http://localhost:8080"
##########################


## pomocnicze funckje
def click_element(by, value, timeout=10):
    try:
        element = WebDriverWait(driver, timeout).until(
            EC.element_to_be_clickable((by, value))
        )
        element.click()
    except (NoSuchElementException, TimeoutException):
        print(f"Element {value} not found.")


def log():
    driver.get(site_url)

    click_element(By.XPATH, '//*[@title="Zaloguj się"]')

    mail_box = driver.find_element(By.NAME, "email")
    mail_box.send_keys("kotek13@gmail.com")

    pass_box = driver.find_element(By.NAME, "password")
    pass_box.send_keys("test1234")

    click_element(By.XPATH, '//button[contains(text(), "Zaloguj się")]')


# #=========================


# a. Dodanie do koszyka 10 produktów z dwóch różnych kategorii
def test_adding_10_products_to_cart():
    driver.get(site_url)

    kategorie_button = driver.find_element(By.ID, "headlink5")

    actions = ActionChains(driver)

    actions.move_to_element(kategorie_button).perform()

    driver.implicitly_wait(1)

    click_element(By.XPATH, '//a[contains(@id, "headercategory343")]')

    products = driver.find_elements(By.CLASS_NAME, "js-product")

    for i in range(6):
        products = driver.find_elements(By.CLASS_NAME, "js-product")

        products[i].click()
        button_to_cart = WebDriverWait(driver, 10).until(
            EC.element_to_be_clickable(
                (
                    By.XPATH,
                    '//a[contains(@class, "elementor-button elementor-size-lg")]',
                )
            )
        )
        button_to_cart.click()

        button_to_continue = WebDriverWait(driver, 10).until(
            EC.element_to_be_clickable(
                (
                    By.XPATH,
                    '//button[contains(@class, "btn btn-secondary") and text()="Kontynuuj zakupy"]',
                )
            )
        )
        button_to_continue.click()
        time.sleep(1)
        driver.back()

    driver.back()

    kategorie_button = driver.find_element(By.ID, "headlink5")

    actions = ActionChains(driver)

    actions.move_to_element(kategorie_button).perform()

    driver.implicitly_wait(1)

    click_element(By.XPATH, '//a[contains(@id, "headercategory341")]')

    products = driver.find_elements(By.CLASS_NAME, "js-product")

    for i in range(4):
        products = driver.find_elements(By.CLASS_NAME, "js-product")

        products[i].click()
        button_to_cart = WebDriverWait(driver, 10).until(
            EC.element_to_be_clickable(
                (
                    By.XPATH,
                    '//a[contains(@class, "elementor-button elementor-size-lg")]',
                )
            )
        )
        button_to_cart.click()

        button_to_continue = WebDriverWait(driver, 10).until(
            EC.element_to_be_clickable(
                (
                    By.XPATH,
                    '//button[contains(@class, "btn btn-secondary") and text()="Kontynuuj zakupy"]',
                )
            )
        )
        button_to_continue.click()
        time.sleep(1)
        driver.back()


# b. Wyszukanie produktu po nazwie i dodanie do koszyka losowego produktu spośród znalezionych
def test_add_one_specified_product_to_cart():
    driver.get(site_url)

    search_box = driver.find_element(By.CLASS_NAME, "search__input")
    search_box.clear()
    search_box.send_keys("stojak")

    search_box.send_keys(Keys.RETURN)

    driver.implicitly_wait(1)

    products = driver.find_elements(
        By.XPATH, '//*[@class="thumbnail product-thumbnail"]'
    )

    random_product = random.choice(products)
    random_product.click()

    button_to_cart = WebDriverWait(driver, 10).until(
        EC.element_to_be_clickable(
            (By.XPATH, '//a[contains(@class, "elementor-button elementor-size-lg")]')
        )
    )
    button_to_cart.click()

    button_to_continue = WebDriverWait(driver, 10).until(
        EC.element_to_be_clickable(
            (
                By.XPATH,
                '//button[contains(@class, "btn btn-secondary") and text()="Kontynuuj zakupy"]',
            )
        )
    )
    button_to_continue.click()
    time.sleep(1)


# # c. Usunięcie z koszyka 3 produktów
def test_remove_3_products_from_cart():
    driver.get(site_url)

    click_element(By.XPATH, '//*[@title="Koszyk"]')

    cart_items = driver.find_elements(By.XPATH, '//li[contains(@class, "cart-item")]')

    for i in range(min(3, len(cart_items))):
        cart_items = driver.find_elements(
            By.XPATH, '//li[contains(@class, "cart-item")]'
        )

        remove_button = cart_items[i].find_element(By.CLASS_NAME, "remove-from-cart")
        remove_button.click()
        time.sleep(1)


# d. Rejestrację nowego konta
def test_registry():
    driver.get(site_url)

    click_element(By.XPATH, '//*[@title="Zarejestruj się"]')

    firstname_box = driver.find_element(By.NAME, "firstname")
    firstname_box.send_keys("Jak")

    lastname_box = driver.find_element(By.NAME, "lastname")
    lastname_box.send_keys("Kowalski")

    mail_box = driver.find_element(By.NAME, "email")
    mail_box.send_keys("jankowalski1@gmail.com") # trzeba zmienić email, bo będzie błąd, że już istnieje

    pass_box = driver.find_element(By.NAME, "password")
    pass_box.send_keys("testpass123")

    check_box = driver.find_element(By.NAME, "customer_privacy")
    if not check_box.is_selected():
        check_box.click()

    check_box = driver.find_element(By.NAME, "psgdpr")
    if not check_box.is_selected():
        check_box.click()

    click_element(By.XPATH, '//button[contains(text(), "Zapisz")]')


# e. Wykonanie zamówienia zawartości koszyka
# f. Wybór metody płatności: przy odbiorze,
# g. Wybór jednego z dwóch przewoźników
# h. Zatwierdzenie zamówienia
def test_making_order():
    click_element(By.XPATH, '//*[@title="Koszyk"]')

    click_element(By.XPATH, '//a[contains(@class, "btn btn-primary")]')

    address_box = driver.find_element(By.NAME, "address1")
    address_box.send_keys("Do studzienki 61")

    postcode_box = driver.find_element(By.NAME, "postcode")
    postcode_box.send_keys("80-830")

    city_box = driver.find_element(By.NAME, "city")
    city_box.send_keys("Gdańsk")

    click_element(By.NAME, "confirm-addresses")

    time.sleep(1)

    delivery_box = driver.find_element(By.ID, "delivery_option_19")
    if not delivery_box.is_selected():
        delivery_box.click()

    click_element(By.NAME, "confirmDeliveryOption")

    payment_box = driver.find_element(By.ID, "payment-option-2")
    if not payment_box.is_selected():
        payment_box.click()

    click_element(By.XPATH, '//button[contains(text(), "Złóż zamówienie")]')


# i. Sprawdzenie statusu zamówienia
def test_check_status_of_ordering():
    driver.get(site_url)

    click_element(By.XPATH, '//a[contains(text(), "Moje konto")]')

    click_element(By.ID, "history-link")

    link = driver.find_element(By.LINK_TEXT, "Szczegóły")
    link.click()

    # j. Pobranie faktury VAT


def test_loading_of_VAT_recipy():
    driver.get(site_url)

    click_element(By.XPATH, '//a[contains(text(), "Moje konto")]')

    click_element(By.ID, "history-link")


test_adding_10_products_to_cart()
test_add_one_specified_product_to_cart()
test_remove_3_products_from_cart()
test_registry()
test_making_order()
test_check_status_of_ordering()
test_loading_of_VAT_recipy()