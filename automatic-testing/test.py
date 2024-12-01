from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.firefox.service import Service
from selenium.webdriver.firefox.options import Options
import time
import random

##############################

firefox_options = Options()
#firefox_options.add_argument("--headless")
firefox_service = Service("/snap/bin/geckodriver")

# Initialization firefox browser
driver = webdriver.Firefox(service=firefox_service, options=firefox_options)

site_url = "http://localhost:8080/search?s=kotek"

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





# a. Dodanie do koszyka 10 produktów z dwóch różnych kategorii

# driver.get(site_url)
# click_element(By.CLASS_NAME, "count")
# products = driver.find_elements()
# for i in range(5):  # Dodanie 5 produktów z pierwszej kategorii
#     products[i].click()
#     time.sleep(1)

# driver.get('https://example.com/kategoria2')  # Zmiana kategorii
# wait_for_element('//button[contains(text(), "Kategoria 2")]', By.XPATH).click()
# time.sleep(2)
# products = driver.find_elements(By.XPATH, '//button[contains(text(), "Dodaj do koszyka")]')
# for i in range(5):  # Dodanie 5 produktów z drugiej kategorii
#     products[i].click()
#     time.sleep(1)



#b. Wyszukanie produktu po nazwie i dodanie do koszyka losowego produktu spośród znalezionych 

# driver.get(site_url)

# search_box = driver.find_element(By.CLASS_NAME, 'search__input')
# search_box.clear() 
# search_box.send_keys('kotek') 

# search_box.send_keys(Keys.RETURN) 

# products = driver.find_elements(By.CLASS_NAME, 'thumbnail')


# if products:
#     ##random_product = random.choice(products)  
#     ##random_product.click() 



#     products[0].click()
        
        
#     button_to_cart = WebDriverWait(driver, 10).until(
#             EC.element_to_be_clickable((By.XPATH, '//a[contains(@class, "elementor-button elementor-size-lg")]'))
#         )
#     button_to_cart.click()

#     button_to_continue = WebDriverWait(driver, 10).until(
#             EC.element_to_be_clickable((By.XPATH, '//button[contains(@class, "btn btn-secondary") and text()="Kontynuuj zakupy"]'))
#         )
#     button_to_continue.click()
       
   
# else:
#     print("Nie znaleziono żadnych produktów!")
    

#c. Usunięcie z koszyka 3 produktów
# driver.get(site_url)

# click_element(By.XPATH, '//*[@title="Koszyk"]')

# cart_items = driver.find_elements(By.XPATH, '//li[contains(@class, "cart-item")]')

# for i in range(min(3, len(cart_items))):
#     cart_items = driver.find_elements(By.XPATH, '//li[contains(@class, "cart-item")]')

#     remove_button = cart_items[i].find_element(By.CLASS_NAME, 'remove-from-cart')
#     remove_button.click()
#     time.sleep(1)
    
#d. Rejestrację nowego konta

# driver.get(site_url)

# click_element(By.XPATH, '//*[@title="Zarejestruj się"]')

# firstname_box = driver.find_element(By.NAME, 'firstname')
# firstname_box.send_keys('kotek') 

# lastname_box = driver.find_element(By.NAME, 'lastname')
# lastname_box.send_keys('kotek') 

# mail_box = driver.find_element(By.NAME, 'email')
# mail_box.send_keys('kotek13@gmail.com') 

# pass_box = driver.find_element(By.NAME, 'password')
# pass_box.send_keys('test1234') 

# check_box = driver.find_element(By.NAME, 'customer_privacy')
# if not check_box.is_selected():  
#     check_box.click()


# check_box = driver.find_element(By.NAME, 'psgdpr')
# if not check_box.is_selected():  
#     check_box.click()

# click_element(By.XPATH, '//button[contains(text(), "Zapisz")]')

#e. Wykonanie zamówienia zawartości koszyka
#g. Wybór jednego z dwóch przewoźników
#h. Zatwierdzenie zamówienia

# click_element(By.XPATH, '//*[@title="Koszyk"]')

# click_element(By.XPATH, '//a[contains(@class, "btn btn-primary")]')

# address_box = driver.find_element(By.NAME, 'address1')
# address_box.send_keys('kotek') 

# postcode_box = driver.find_element(By.NAME, 'postcode')
# postcode_box.send_keys('12-345') 

# city_box = driver.find_element(By.NAME, 'city')
# city_box.send_keys('kotek') 

# click_element(By.NAME, 'confirm-addresses')

# time.sleep(1)

# delivery_box = driver.find_element(By.ID, 'delivery_option_19')
# if not delivery_box.is_selected():  
#     delivery_box.click()

# click_element(By.NAME, 'confirmDeliveryOption')

# payment_box = driver.find_element(By.ID, 'payment-option-2')
# if not payment_box.is_selected():  
#     payment_box.click()

# click_element(By.XPATH, '//button[contains(text(), "Złóż zamówienie")]')




#=============================
# Logowanie się
#===========================

driver.get(site_url)

click_element(By.XPATH, '//*[@title="Zaloguj się"]')

mail_box = driver.find_element(By.NAME, 'email')
mail_box.send_keys('kotek13@gmail.com') 


pass_box = driver.find_element(By.NAME, 'password')
pass_box.send_keys('test1234') 


click_element(By.XPATH, '//button[contains(text(), "Zaloguj się")]')




#=========================


# i. Sprawdzenie statusu zamówienia


driver.get(site_url)

click_element(By.XPATH, '//a[contains(text(), "Moje konto")]')

click_element(By.ID, "history-link")

link = driver.find_element(By.LINK_TEXT, "Szczegóły")
link.click()

# Pobranie faktury VAT

driver.get(site_url)

click_element(By.XPATH, '//a[contains(text(), "Moje konto")]')

click_element(By.ID, "order-slips-link")