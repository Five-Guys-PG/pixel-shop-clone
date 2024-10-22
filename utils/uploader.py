from typing import List, TypedDict

from prestapyt import PrestaShopWebServiceDict, PrestaShopWebServiceError

product_data = {
    'product': {
        'name': {
            'language': [{'attrs': {'id': '1'}, 'value': 'My Product Name'}]
        },
        'price': '19.99',
        'description': {
            'language': [{'attrs': {'id': '1'}, 'value': 'This is the product description'}]
        },
        'description_short': {
            'language': [{'attrs': {'id': '1'}, 'value': 'Short description here'}]
        },
    }
}


class Uploader:
    def __init__(self, api_url: str, api_key: str):
        self._prestashop = PrestaShopWebServiceDict(api_url, api_key)
        blank_schema = self._prestashop.get('products', options={'schema': 'blank'})
        print(blank_schema)

    def upload_products(self, product_data_source):
        for product in product_data_source:
            self._prestashop.add('products', product)

if __name__ == '__main__':
    uploader = Uploader('http://localhost:8080/api', '6HXFVWL5NH8S9FV5JEAIAILMWR6HSBPV')
