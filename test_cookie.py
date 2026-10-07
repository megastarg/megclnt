import re
import json
from curl_cffi import requests

url = "https://www.flipkart.com/televisions/pr?sid=ckf%2Cczl&sort=price_asc"
headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36",
    "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7",
    "Accept-Language": "en-GB,en;q=0.9",
    "Cookie": "dpr=2;AMCV_17EB401053DAF4840A490D4C%40AdobeOrg=-227196251%7CMCIDTS%7C20726%7CMCMID%7C29888213129656263122023191179566901001%7CMCAAMLH-1791279169%7C12%7CMCAAMB-1791279169%7CRKhpRz8krg2tLO6pguXWp5olkAcUniQYPHaMWWgdJ3xzPWQmdj0y%7CMCOPTOUT-1790681569s%7CNONE%7CMCAID%7CNONE;T=TI177412150422400119225761751852768802943581962952752739328126978160;vh=691;S=d1t13Pz8/f3M/RD9PP0g/Nj8/P8RF2tDpNMOggYX+ENUTk/DWW8q/xGQSNk1YapOIh111+CSuAdIAG3FeZWuNpY/GdA==;ud=8.H-ln8QiRdJI2Sd_6H6Ww6mCGs5k2lJvvabgQRdssG5321-ouTQpXVTW2mrutYyTqIrOy6X9zTCAaFhCdoTqh6VkUtn0kyE-T1UDxdUuAjW2UY5peiXJZcD6UDxdFK4DHy2olDKqi8Y4iVr6jHLK0RYw0SB3i9XvoWqz3MPcJ5Cg_eW-qkUKxkZUf9smtXJ6GQY7dC3j7oOs5ZgCi8mSgzGclsZ9AACbNb9-vPG8EruOVYyquuzdDpsw9kKSxSejx5uNZH-Qyb4fEza3q0lSxoolJDTziTR68D9htwMJahrJPL9YzJomGF8YRzMpknqcH14jFLcEu2la38-YjzLhQJcdcY0BBzeOocgoBXtYsVevMblIep-fxD07UmUE947gjvuZz1v5yQ4j7p3zfT-hUxRauWiWve55UcF0WS2vPz3T0I0RYJil3_dEfygtVOMjZb_SbZImzENGfMks-lX0VHVrtj2pZisOGfoYzeCTPdw6UBJ8ZNs0OsVboRw7IzYxBmSLlctT4vQwSSaVbKzOx6qAfixbeY1Zk2Rmnw2xqhh05QYBDs732cjbc6LH5qjq8bdAxzHRrIeLdTImDejip4Jvd75qUeejP5xzUV3dKeCikqtrYpNpO3zI7eXmtWz17pkT1QcLnIYV7p0WP3OVwmOzP1HN-fGYElCVYhXjR9apWfczFC3tzxcl5jBiky6exmetMyRrbo2kssK7Nqdhm6yEzUJ6y2ereHWHzxhrpHGzkwRDAn_Sn7MXhmo2FjAV1U5dJCFjncgtPGsR7snACKWl69EcWycXg0urra-tDnZSdDG_8nopG9u3EHJQRacWi;ak_bmsc=5685BD1B6E3F9BD2DCBB9DDD586A651F~000000000000000000000000000000~YAAQrPQ3F+9cJ+qgAQAA3Fz/8AHde2Hbf1ChNf0SF8uYAaG2KfRZW3gONAvPBynBiLrjElV+owCoWn2LYqP33dozaO1WvJ4vWGahadZLSvh2Qk1iONMfFkLkOyo7WG/ylNleneZ3VGkx7dz+0Q8MrC9DFe1Pcez8MgyNLGV1VumVJmuDuvIcoIVqi496fK7GWWBZDZcNdBTAZ2yigNnFe/u6860io3Zxs731q+cSd/J1/U71sdmycEo/4m38+gIbZ6JvrvN1TN1iG0iN1xcyG1EBjDyQrQWDXBioQ53ZxHAM5nSpYOMB6E+czj1hhS2rCAjxRQYdq2LlhH7Gj10m9BowD7nBvoQNhZ+I7WW6BWhoQEBjKl4uQ9DHRh8RHjanPQkaBJtHWK053deBxzC+rQ==;rt=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6IjhlM2ZhMGE3LTJmZDMtNGNiMi05MWRjLTZlNTMxOGU1YTkxZiJ9.eyJleHAiOjE4MDYzODgwNjIsImlhdCI6MTc5MDc0OTY2MiwiaXNzIjoia2V2bGFyIiwianRpIjoiZGU1MzBkYWItOGFlOC00MjZjLTgxMjItZDYwNzQ3ZWIzYmE2IiwidHlwZSI6IlJUIiwiZElkIjoiVEkxNzc0MTIxNTA0MjI0MDAxMTkyMjU3NjE3NTE4NTI3Njg4MDI5NDM1ODE5NjI5NTI3NTI3MzkzMjgxMjY5NzgxNjAiLCJiSWQiOiJaRlI4V0QiLCJrZXZJZCI6IlZJMkYxMkIyQTM0RjU1NDRDQkEwNEQwNzcyNjY2MjM4NzUiLCJ0SWQiOiJtYXBpIiwibSI6eyJ0eXBlIjoibiJ9LCJ2IjoiNkxNR0tUIiwiZG9tIjoiRkxJUEtBUlQifQ.CckSTOPCElmQQYEIVLp6883oMAweL0hNdFhaP8XQXL0;at=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6IjhlM2ZhMGE3LTJmZDMtNGNiMi05MWRjLTZlNTMxOGU1YTkxZiJ9.eyJleHAiOjE3OTA3NTE0NjIsImlhdCI6MTc5MDc0OTY2MiwiaXNzIjoia2V2bGFyIiwianRpIjoiOGY2NDRmZDMtMzQ3Mi00NWE4LTg0YWUtZjgxNGNmZjllYWQ5IiwidHlwZSI6IkFUIiwiZElkIjoiVEkxNzc0MTIxNTA0MjI0MDAxMTkyMjU3NjE3NTE4NTI3Njg4MDI5NDM1ODE5NjI5NTI3NTI3MzkzMjgxMjY5NzgxNjAiLCJiSWQiOiJaRlI4V0QiLCJrZXZJZCI6IlZJMkYxMkIyQTM0RjU1NDRDQkEwNEQwNzcyNjY2MjM4NzUiLCJ0SWQiOiJtYXBpIiwiZWFJZCI6InVJSUJPTDBPSGdLaHhGR1BDdXUxQVZsU1R1V3JZSkpyZGYybHdxcnBOX0dWZVNlNHVYVU1BQT09IiwidnMiOiJMSSIsInoiOiJIWUQiLCJtIjp0cnVlLCJnZW4iOjMsImRvbSI6IkZMSVBLQVJUIn0.I4dxqSXEpFsU1v02rCFWwrtQV_Hh0ZzGf6C0wlAmTgU;vw=1440;K-ACTION=null;_gcl_au=1.1.1088474976.1790024895;bm_sv=01881C4E311B5FAF3A86DC17E798118A~YAAQrPQ3F8pzJ+qgAQAANqP/8AHB4qXoCF5mA+Cn57yI0Xn+GevRGYps737JdAhEyz25Dr5OmcxemmwSA3VJCMWua/Drt3u1+MS7jtHqqYzUFcocxwdUfy8wY6yrBgkQQ2rkyPxFz40oI2u0VuayrbMbxuxrc+baHkhdCfLGYEWaqDVBcUWAGCPEa9tqsMIjOS/ZCRueXDJ959ptxQINm4NxOaf3CZ/nGxjF4jDBFq9LSxO1wnuFcl5jGNpwQjdAXgQ=~1;Network-Type=4g;SN=VI2F12B2A34F5544CBA04D077266623875.TOKFD010ACB0BD94A41948C90E2431AF5AA.1790749680666.LI;vd=VI2F12B2A34F5544CBA04D077266623875-1774121506818-37.1790749662.1790749662.154691417"
}

r = requests.get(url, headers=headers, impersonate="chrome110")
html = r.text
print("Title:", re.search(r'<title>(.*?)</title>', html).group(1) if re.search(r'<title>(.*?)</title>', html) else "No title")

mat = re.search("__INITIAL_STATE__ = (.*?)};", html)
if mat:
    mat = mat[1] + "}"
    jsonarray = json.loads(mat)
    try:
        pages = jsonarray.get("pageDataV4", {}).get("page", {}).get("data", {})
        print("Keys in pageDataV4.page.data:", pages.keys())
        if "10003" in pages:
            print("Found 10003!")
            products = pages["10003"]
            print("Number of sections in 10003:", len(products))
    except Exception as e:
        print("Error getting pages", e)
