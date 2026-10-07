import json

with open("test_dump.json", "r") as f:
    data = json.load(f)
    root = data["pageDataV4"]["page"]["data"]["ROOT"]
    print(type(root))
    if isinstance(root, list):
        print(len(root))
    
    # Check if there is a 'slots' or 'widgets' key anywhere in root if it's a dict
    
