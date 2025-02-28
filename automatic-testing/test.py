import pytest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.firefox.service import Service
from selenium.webdriver.common.action_chains import ActionChains
from selenium.common.exceptions import NoSuchElementException, TimeoutException
from selenium.webdriver.firefox.options import Options
import time
import random

##############################
firefox_options = Options()
# firefox_options.add_argument("--headless")
firefox_service = Service("/snap/bin/geckodriver")

site_url = "https://localhost:8083"
##########################

driver = webdriver.Firefox(service=firefox_service, options=firefox_options)

## pomocnicze funckje
def click_element(by, value, driver, timeout=10):
    try:
        element = WebDriverWait(driver, timeout).until(
            EC.element_to_be_clickable((by, value))
        )
        element.click()
    except (NoSuchElementException, TimeoutException):
        print(f"Element {value} not found.")


def log():
    driver.get(site_url)

    click_element(By.XPATH, '//*[@title="Zaloguj się"]', driver)

    mail_box = driver.find_element(By.NAME, "email")
    mail_box.send_keys("jankowalski7@gmail.com")

    pass_box = driver.find_element(By.NAME, "password")
    pass_box.send_keys("testpass123")

    click_element(By.XPATH, '//button[contains(text(), "Zaloguj się")]', driver)
# #=========================


# a. Dodanie do koszyka 10 produktów z dwóch różnych kategorii
def test_adding_10_products_to_cart():
    driver.get(site_url)
    time.sleep(1)
    kategorie_button = driver.find_element(By.ID, "headlink5")

    actions = ActionChains(driver)

    actions.move_to_element(kategorie_button).perform()

    driver.implicitly_wait(1)
    time.sleep(1)
    elements = driver.find_elements(By.XPATH, "//*[starts-with(@id, 'headercategory')]")

    elements[3].click()

    

    products = driver.find_elements(By.XPATH, '//*[@class="thumbnail product-thumbnail"]')

    for i in range(6):
        
        products = driver.find_elements(By.XPATH, '//*[@class="thumbnail product-thumbnail"]')

        time.sleep(1)
        products[i].click()
        button_to_cart = WebDriverWait(driver, 10).until(
            EC.element_to_be_clickable(
                (
                    By.XPATH,
                    '//a[contains(@class, "elementor-button elementor-size-lg")]',
                )
            )
        )
        time.sleep(1)
        button_to_cart.click()

        button_to_continue = WebDriverWait(driver, 10).until(
            EC.element_to_be_clickable(
                (
                    By.XPATH,
                    '//button[contains(@class, "btn btn-secondary") and text()="Kontynuuj zakupy"]',
                )
            )
        )
        time.sleep(1)
        button_to_continue.click()
        time.sleep(1)
        driver.back()

    driver.back()

    kategorie_button = driver.find_element(By.ID, "headlink5")

    actions = ActionChains(driver)

    actions.move_to_element(kategorie_button).perform()

    driver.implicitly_wait(1)

    elements = driver.find_elements(By.XPATH, "//*[starts-with(@id, 'headercategory')]")
    time.sleep(1)
    elements[7].click()

    #click_element(By.XPATH, '//a[contains(@id, "headercategory71")]', driver)

    products = driver.find_elements(By.XPATH, '//*[@class="thumbnail product-thumbnail"]')

    for i in range(4):
        products = driver.find_elements(By.XPATH, '//*[@class="thumbnail product-thumbnail"]')
        time.sleep(1)
        products[i].click()
        button_to_cart = WebDriverWait(driver, 10).until(
            EC.element_to_be_clickable(
                (
                    By.XPATH,
                    '//a[contains(@class, "elementor-button elementor-size-lg")]',
                )
            )
        )
        time.sleep(1)
        button_to_cart.click()

        button_to_continue = WebDriverWait(driver, 10).until(
            EC.element_to_be_clickable(
                (
                    By.XPATH,
                    '//button[contains(@class, "btn btn-secondary") and text()="Kontynuuj zakupy"]',
                )
            )
        )
        time.sleep(2)
        button_to_continue.click()
        time.sleep(2)
        driver.back()

    driver.get(site_url)
    time.sleep(2)
    click_element(By.XPATH, '//*[@title="Koszyk"]', driver)

    counter = 10

    for i in range(10):

        random_quantity = random.randint(1, 5)

        time.sleep(1)
        buttons = driver.find_elements(By.CSS_SELECTOR, 'button.btn.btn-touchspin.js-touchspin.js-increase-product-quantity.bootstrap-touchspin-up')
        
        if buttons:
            for _ in range(random_quantity - 1):
                time.sleep(1)
                buttons = driver.find_elements(By.CSS_SELECTOR, 'button.btn.btn-touchspin.js-touchspin.js-increase-product-quantity.bootstrap-touchspin-up')  
                
                buttons[i].click()
                counter+=1

    
    driver.get(site_url)
    time.sleep(2)
    count_of_products_in_cart = driver.find_element(By.XPATH, '//span[contains(@class, "countlabel")]').text.split()[-1] 


    assert count_of_products_in_cart == f'({counter})'

# b. Wyszukanie produktu po nazwie i dodanie do koszyka losowego produktu spośród znalezionych
def test_add_one_specified_product_to_cart():
    driver.get(site_url)
    time.sleep(2)
    count_of_products_in_cart_before = int(driver.find_element(By.XPATH, '//span[contains(@class, "countlabel")]').text.split()[-1][1:-1])

    search_box = driver.find_element(By.CLASS_NAME, "search__input")
    search_box.clear()
    search_box.send_keys("kielich")

    search_box.send_keys(Keys.RETURN)

    driver.implicitly_wait(2)

    products = driver.find_elements(
        By.XPATH, '//*[@class="thumbnail product-thumbnail"]'
    )

    random_product = random.choice(products)
    time.sleep(2)
    random_product.click()
    time.sleep(2)
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
    time.sleep(2)
    button_to_continue.click()
    time.sleep(2)

    count_of_products_in_cart_after = int(driver.find_element(By.XPATH, '//span[contains(@class, "countlabel")]').text.split()[-1][1:-1])

    assert count_of_products_in_cart_after == count_of_products_in_cart_before + 1


# c. Usunięcie z koszyka 3 produktów
def test_remove_3_products_from_cart():
    driver.get(site_url)
    time.sleep(2)
    click_element(By.XPATH, '//*[@title="Koszyk"]', driver)

    number_of_cart_items_before = len(driver.find_elements(By.XPATH, '//li[contains(@class, "cart-item")]'))

    cart_items = driver.find_elements(By.XPATH, '//li[contains(@class, "cart-item")]')

    for i in range(min(3, len(cart_items))):
        cart_items = driver.find_elements(
            By.XPATH, '//li[contains(@class, "cart-item")]'
        )

        remove_button = cart_items[i].find_element(By.CLASS_NAME, "remove-from-cart")
        remove_button.click()
        time.sleep(2)

    

    number_of_cart_items_after = len(driver.find_elements(By.XPATH, '//li[contains(@class, "cart-item")]'))

    assert number_of_cart_items_after+3 == number_of_cart_items_before


# d. Rejestrację nowego konta
def test_registry():
    driver.get(site_url)
    time.sleep(1)
    click_element(By.XPATH, '//*[@title="Zarejestruj się"]', driver)
    time.sleep(1)
    firstname_box = driver.find_element(By.NAME, "firstname")
    firstname_box.send_keys("Jan")

    lastname_box = driver.find_element(By.NAME, "lastname")
    lastname_box.send_keys("Kowalski")

    mail_box = driver.find_element(By.NAME, "email")

    milli_sec = int(round(time.time() * 1000))

    mail_box.send_keys("jakowalski" + str(milli_sec) + "@gmail.com") # trzeba zmienić email, bo będzie błąd, że już istnieje

    pass_box = driver.find_element(By.NAME, "password")
    pass_box.send_keys("testpass123")

    check_box = driver.find_element(By.NAME, "customer_privacy")
    if not check_box.is_selected():
        check_box.click()

    check_box = driver.find_element(By.NAME, "psgdpr")
    if not check_box.is_selected():
        check_box.click()

    click_element(By.XPATH, '//button[contains(text(), "Zapisz")]', driver)

    try:
        account = driver.find_element(By.XPATH, '//*[@title="Zarejestruj się"]')
        assert 1 == 0, "Zarejestruj sie przycisk jest widoczny"
    except NoSuchElementException:
        pass
    


# e. Wykonanie zamówienia zawartości koszyka
# f. Wybór metody płatności: przy odbiorze,
# g. Wybór jednego z dwóch przewoźników
# h. Zatwierdzenie zamówienia
def test_making_order():
    time.sleep(1)
    click_element(By.XPATH, '//*[@title="Koszyk"]', driver)
    time.sleep(1)
    click_element(By.XPATH, '//a[contains(@class, "btn btn-primary")]', driver)
    time.sleep(1)
    address_box = driver.find_element(By.NAME, "address1")
    address_box.send_keys("Do studzienki 61")

    postcode_box = driver.find_element(By.NAME, "postcode")
    postcode_box.send_keys("80-830")

    city_box = driver.find_element(By.NAME, "city")
    city_box.send_keys("Gdańsk")
    time.sleep(1)
    click_element(By.NAME, "confirm-addresses", driver)

    time.sleep(1)

    delivery_box = driver.find_element(By.ID, "delivery_option_19")
    if not delivery_box.is_selected():
        delivery_box.click()
    time.sleep(1)
    click_element(By.NAME, "confirmDeliveryOption", driver)
    time.sleep(1)
    payment_box = driver.find_element(By.ID, "payment-option-2")
    if not payment_box.is_selected():
        payment_box.click()
    time.sleep(1)
    click_element(By.XPATH, '//button[contains(text(), "Złóż zamówienie")]', driver)


# i. Sprawdzenie statusu zamówienia
def test_check_status_of_ordering():
    driver.get(site_url)
    time.sleep(1)
    click_element(By.XPATH, '//a[contains(text(), "Moje konto")]', driver)
    time.sleep(1)
    click_element(By.ID, "history-link", driver)
    time.sleep(1)
    link = driver.find_element(By.LINK_TEXT, "Szczegóły")
    link.click()
    time.sleep(2)
    # j. Pobranie faktury VAT


def test_loading_of_VAT_recipy():
    driver.get(site_url)
    time.sleep(1)

    click_element(By.XPATH, '//a[contains(text(), "Moje konto")]', driver)
    time.sleep(1)
    click_element(By.ID, "history-link", driver)
    time.sleep(1)
    link = driver.find_element(By.XPATH, "//td[@class='text-sm-center hidden-md-down']/a")
    link.click()
