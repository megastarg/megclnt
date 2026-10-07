import json

def find_products(d, path):
    if isinstance(d, dict):
        if "products" in d and isinstance(d["products"], list):
            print("Found products list at path:", path, "length:", len(d["products"]))
        for k, v in d.items():
            find_products(v, path + [str(k)])
    elif isinstance(d, list):
        for i, v in enumerate(d):
            find_products(v, path + [str(i)])

with open("api_response.json", "r") as f:
    data = json.load(f)
    find_products(data, [])
