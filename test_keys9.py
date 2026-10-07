from bs4 import BeautifulSoup

with open("test.html") as f:
    html = f.read()
    
soup = BeautifulSoup(html, "html.parser")
scripts = soup.find_all("script")
for i, s in enumerate(scripts):
    text = s.string
    if text and len(text) > 100:
        print(f"Script {i}: {len(text)} bytes, starts with {text[:50]}")
