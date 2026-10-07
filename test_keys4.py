import json

def find_products(d, path):
    if isinstance(d, dict):
        if "products" in d and isinstance(d["products"], list):
            print("Found products list at path:", path, "length:", len(d["products"]))
            if len(d["products"]) > 0:
                print("Sample keys of first product:", d["products"][0].keys() if isinstance(d["products"][0], dict) else "Not dict")
        for k, v in d.items():
            find_products(v, path + [str(k)])
    elif isinstance(d, list):
        for i, v in enumerate(d):
            find_products(v, path + [str(i)])

with open("test_dump.json", "r") as f:
    data = json.load(f)
    find_products(data, ["ROOT"])
    
