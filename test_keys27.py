from curl_cffi import requests
import re
url = "https://www.flipkart.com/mobiles/pr?sid=tyy%2C4io&marketplace=FLIPKART&sort=price_asc"
headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36"
}
r = requests.get(url, headers=headers, impersonate="chrome110")
print(re.search(r'<title>(.*?)</title>', r.text).group(1))
