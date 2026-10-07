import re
from bs4 import BeautifulSoup
import json

with open("test.html", "r") as f:
    html = f.read()

# Let's search the HTML for some product names or price or "products":
print("Occurrences of 'products':", html.count("products"))
print("Occurrences of '10003':", html.count("10003"))
print("Occurrences of 'pageDataV4':", html.count("pageDataV4"))

