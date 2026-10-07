import re
import json
from curl_cffi import requests

url = "https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&sort=price_asc"
headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36",
    "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
    "Accept-Language": "en-GB,en;q=0.9",
    "Referer": "https://www.flipkart.com/",
}

with open("cookie.txt", "r") as f:
    lines = f.readlines()
    if len(lines) >= 3:
        headers["Cookie"] = lines[2].strip()

for imp in ["chrome110", "chrome116", "chrome120", "safari15_3", "safari17_0"]:
    try:
        r = requests.get(url, headers=headers, impersonate=imp, timeout=15)
        title_match = re.search(r'<title>(.*?)</title>', r.text)
        title = title_match.group(1) if title_match else "No title"
        if "Samsung" in title or "Televisions" in title:
            print(f"SUCCESS with {imp}! Title: {title}")
            break
        else:
            print(f"Failed {imp}. Title: {title}")
    except Exception as e:
        print(f"Error {imp}: {e}")
