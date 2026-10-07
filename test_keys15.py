import httpx
import re

url = "https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&sort=price_asc"
headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36",
    "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
    "Accept-Language": "en-US,en;q=0.9"
}

with open("cookie.txt") as fff:
    cokie = fff.readlines()
headers["Cookie"] = cokie[2].strip()

r = httpx.get(url, headers=headers, timeout=30)
html = r.text
print("Status:", r.status_code)
print("Title:", re.search(r'<title>(.*?)</title>', html).group(1) if re.search(r'<title>(.*?)</title>', html) else "No title")
