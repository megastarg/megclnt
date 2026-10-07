import json

def search_term(d, path, term):
    if isinstance(d, dict):
        for k, v in d.items():
            search_term(v, path + [str(k)], term)
    elif isinstance(d, list):
        for i, v in enumerate(d):
            search_term(v, path + [str(i)], term)
    elif isinstance(d, str):
        if term.lower() in d.lower():
            print("Found at path:", " -> ".join(path))
            print("Value:", d)

with open("api_response.json", "r") as f:
    data = json.load(f)
    search_term(data, [], "Samsung")
