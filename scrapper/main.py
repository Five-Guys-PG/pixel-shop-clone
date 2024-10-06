import requests
from bs4 import BeautifulSoup

site_url = 'https://pixel-shop.pl'

def scrape_delivery_info(delivery_div):
    delivery_data = []

    # Extract the delivery methods
    rows = delivery_div.find_all('div', class_='row-delivery')
    for i in range(0, len(rows), 2):  # Step by 2 to get payment method and its associated delivery options
        payment_method = rows[i].find('div', class_='col-delivery').text.strip()
        delivery_options = []

        # Get the delivery options
        for j in range(i + 1, min(i + 5, len(rows))):  # Limit to 4 delivery options for each payment method
            img_tag = rows[j].find('img')['src'] if rows[j].find('img') else 'No image'
            price = rows[j].find('div', class_='delivery-value').text.strip() if rows[j].find('div', class_='delivery-value') else 'No price'
            delivery_options.append({
                'method': img_tag,
                'price': price
            })

        delivery_data.append({
            'payment_method': payment_method,
            'delivery_options': delivery_options
        })

    return delivery_data

def scrape_product_details(product_url):
    full_url = site_url + product_url
    response = requests.get(full_url)
    
    if response.status_code == 200:
        product_soup = BeautifulSoup(response.content, 'html.parser')
        availability_tag = product_soup.find('div', class_='availability')
        is_available = availability_tag.find('span', class_="second").text.strip() if availability_tag else 'No information'

        number_of_bought_tag = product_soup.find('div', class_='mx_count')
        b_tags = number_of_bought_tag.find_all('b') if number_of_bought_tag else None
        number_of_people = b_tags[0].text.strip() if b_tags else 'No information'
        number_of_items = b_tags[1].text.strip() if b_tags else 'No information'

        delivery_div = product_soup.find('div', class_='table-delivery-wrapper')
        delivery_info = scrape_delivery_info(delivery_div) if delivery_div else 'No delivery information'

        # Extract product description
        description_div = product_soup.find('div', class_='resetcss', itemprop='description')
        description = description_div.get_text(strip=True) if description_div else 'No description'

        return {
            'is_available': is_available,
            'number_of_people': number_of_people,
            'number_of_items': number_of_items,
            'delivery_info': delivery_info,
            'description': description
        }
    else:
        print(f"Failed to retrieve {full_url}. Status code: {response.status_code}")
        return None

def extract_product_info(product):
    item_name = product.find('span', class_='productname').text.strip()
    
    price_tag = product.find('div', class_='price f-row').find('em')
    item_price = price_tag.text.strip() if price_tag else ''
    
    previous_price_tag = product.find('del', class_='price__inactive')
    previous_price = previous_price_tag.text.strip() if previous_price_tag else ''

    img_tag = product.find('img')
    first_image = site_url + img_tag.get('data-src') if img_tag and img_tag.get('data-src') else 'Image not found'
    second_image = site_url + img_tag.get('data-src-alt') if img_tag and img_tag.get('data-src-alt') else 'Image not found'

    details_link = product.find('a').get('href')

    detailed_info = scrape_product_details(details_link)

    return {
        'name': item_name,
        'price': item_price,
        'previous_price': previous_price,
        'first_image': first_image,
        'second_image': second_image,
        'details': detailed_info
    }

def scrape_category_page(category_url):
    full_url = site_url + category_url
    response = requests.get(full_url)
    
    if response.status_code == 200:
        category_soup = BeautifulSoup(response.content, 'html.parser')
        
        items = category_soup.find_all(class_='product')

        for item in items:
            item_info = extract_product_info(item)
            print(item_info)
    else:
        print(f"Failed to retrieve {full_url}. Status code: {response.status_code}")

# Send a request to the main page
response = requests.get(site_url)

if response.status_code == 200:
    # Parse the main page content
    soup = BeautifulSoup(response.content, 'html.parser')

    submenu_items = soup.find_all('div', class_='submenu level1')

    for submenu in submenu_items:
        # For each submenu, find the links
        links = submenu.find_all('a')
        
        for link in links:
            category_name = link.find('span').text.strip()
            category_link = link.get('href')

            print(f"Scraping category: {category_name}")
            print(f"Link: {category_link}")
            print('-' * 40)
            
            # Scrape the individual category page
            scrape_category_page(category_link)

else:
    print(f"Failed to retrieve the main page. Status code: {response.status_code}")
