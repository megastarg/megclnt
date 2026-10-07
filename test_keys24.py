from curl_cffi import requests
import re

url = "https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&sort=price_asc"

for imp in ["chrome104", "chrome110", "chrome116", "chrome120", "safari15_3", "safari17_0"]:
    try:
        r = requests.get(url, impersonate=imp, timeout=10)
        title_match = re.search(r'<title>(.*?)</title>', r.text)
        title = title_match.group(1) if title_match else "No title"
        if "Samsung" in title or "Televisions" in title:
            print(f"Success with {imp}! Title: {title}")
        else:
            print(f"Failed with {imp}. Title: {title[:30]}...")
    except Exception as e:
        print(f"Error with {imp}: {e}")
