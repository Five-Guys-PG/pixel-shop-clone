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
# for i in range(5):
#     driver.get(site_url)

#     search_box = driver.find_element(By.CLASS_NAME, 'search__input')
#     search_box.clear() 
#     search_box.send_keys('kotek') 

#     search_box.send_keys(Keys.RETURN) 

#     products = driver.find_elements(By.CLASS_NAME, 'thumbnail')


#     if products:
#         ##random_product = random.choice(products)  
#         ##random_product.click() 



#         products[i].click()
        
        
#         button_to_cart = WebDriverWait(driver, 10).until(
#                 EC.element_to_be_clickable((By.XPATH, '//a[contains(@class, "elementor-button elementor-size-lg")]'))
#             )
#         button_to_cart.click()

#         button_to_continue = WebDriverWait(driver, 10).until(
#                 EC.element_to_be_clickable((By.XPATH, '//button[contains(@class, "btn btn-secondary") and text()="Kontynuuj zakupy"]'))
#             )
#         button_to_continue.click()
       
   
#     else:
#         print("Nie znaleziono żadnych produktów!")
    
#     time.sleep(0.5)
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
driver.get(site_url)

click_element(By.XPATH, '//*[@title="Zarejestruj się"]')

search_box = driver.find_element(By.NAME, 'firstname')
search_box.clear() 
search_box.send_keys('kotek') 

search_box = driver.find_element(By.NAME, 'lastname')
search_box.clear() 
search_box.send_keys('kotek') 

search_box = driver.find_element(By.NAME, 'email')
search_box.clear() 
search_box.send_keys('kotek') 

search_box = driver.find_element(By.NAME, 'password')
search_box.clear() 
search_box.send_keys('kotek') 

search_box = driver.find_element(By.NAME, 'customer_privacy')



search_box = driver.find_element(By.NAME, 'psgdpr')



