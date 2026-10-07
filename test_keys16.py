import httpx
import re

url = "https://m.flipkart.com/televisions/pr?sid=ckf%2Cczl&sort=price_asc"
headers = {
    "User-Agent": "Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1"
}

r = httpx.get(url, headers=headers, timeout=30)
html = r.text
print("Status:", r.status_code)
print("Title:", re.search(r'<title>(.*?)</title>', html).group(1) if re.search(r'<title>(.*?)</title>', html) else "No title")
