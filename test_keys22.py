import json

with open("api_response.json", "r") as f:
    data = json.load(f)
    print("Slots:", len(data["RESPONSE"]["slots"]))
    for i, slot in enumerate(data["RESPONSE"]["slots"]):
        print(f"Slot {i}:")
        if "widget" in slot and slot["widget"]:
            print("  Widget type:", slot["widget"].get("type"))
            # See if products are inside
            if "data" in slot["widget"]:
                d = slot["widget"]["data"]
                # Print some keys of data
                print("  Widget data keys:", list(d.keys())[:5])
