"""Fake Bitrix24 for testing. Mimics the response shapes of the real REST API."""
import json, os, random, sys
from http.server import BaseHTTPRequestHandler, ThreadingHTTPServer

random.seed(7)
FLAG_FAIL = os.environ.get("MOCK_FAIL_FLAG", "/tmp/mock_fail")
PORT = int(os.environ.get("MOCK_PORT", "9000"))
N = 1193

STAGES = [("C46:NEW", "New", 10), ("C46:PREPARATION", "Documents", 20),
          ("C46:PAID", "Semester paid", 30), ("C46:WON", "Completed", 40), ("C46:LOSE", "Dropped", 50), ("C46:APOLOGY", "Refund", 60), ("C46:PEND", "Payment pending", 25)]
UNI = [("11", "Alpha University"), ("12", "Beta University"), ("13", "Gamma University")]
BATCH = [("21", "January 2025"), ("22", "July 2025"), ("23", "January 2026")]
COURSE = [("31", "MBA"), ("32", "BBA"), ("33", "BCA"), ("34", "MCA"), ("35","B.COM"),("36","BA - JMC"),("37","BBA + MBA"),("38","MA - PSYCHOLOGY"),("39","MBA - HR ANALYTICS"),("40","DUAL MBA"),("41","MS DS"),("42","BA")]  # MOREPROG
MODE = [("41", "Semester"), ("42", "Annual"), ("43", "Lumpsum")]
SEMS = [("51", "Sem 1"), ("52", "Sem 2"), ("53", "Sem 3")]
PAY = [("61", "Paid"), ("62", "Not Paid")]
SEM_KEYS = ["UF_CRM_1784188741648", "UF_CRM_1784188780998", "UF_CRM_1784188805532"]

def enum(title, items):
    return {"type": "enumeration", "title": title, "formLabel": title, "listLabel": title,
            "items": [{"ID": i, "VALUE": v} for i, v in items]}

FIELDS = {
    "ID": {"type": "integer", "title": "ID"},
    "TITLE": {"type": "string", "title": "Deal name"},
    "STAGE_ID": {"type": "crm_status", "title": "Stage"},
    "CATEGORY_ID": {"type": "crm_category", "title": "Pipeline"},
    "CONTACT_ID": {"type": "crm_contact", "title": "Contact"},
    "DATE_CREATE": {"type": "datetime", "title": "Created on"},
    "OPPORTUNITY": {"type": "double", "title": "Amount"},
    "UF_CRM_1784702446": enum("University Name", UNI),
    "UF_CRM_1784713245": enum("Batch", BATCH),
    "UF_CRM_1784702837": enum("Program Name", COURSE),
    "UF_CRM_1784712099": enum("Mode of Payment", MODE),
    "UF_CRM_1784697183": enum("Current Semester", SEMS),
    "UF_CRM_1782985117": {"type": "date", "title": "Re-registration date", "formLabel": "Re-registration date"},
    "UF_CRM_1782985171": {"type": "date", "title": "Last payment date", "formLabel": "Last payment date"},
    "UF_CRM_1773218485649": {"type": "boolean", "title": "Has Reference", "formLabel": "Has Reference"},
    "UF_CRM_1784701507": {"type": "string", "title": "UF_CRM_1784701507", "formLabel": "Comment History(G)", "listLabel": "Comment History(G)"},
}
for i, k in enumerate(SEM_KEYS):
    FIELDS[k] = enum("Semester %d Status" % (i + 1), PAY)
FIELDS["UF_CRM_1782968155"] = {"type": "boolean", "title": "Reference admission", "formLabel": "Reference admission"}
FIELDS["UF_CRM_REFLEAD"] = {"type": "string", "title": "Reference Lead", "formLabel": "Reference Lead"}
FIELDS["UF_CRM_REFPAID"] = {"type": "double", "title": "Reference Money Paid", "formLabel": "Reference Money Paid"}
DOCK = {"UF_CRM_DOC10": "10th certificate", "UF_CRM_DOC12": "12th / Diploma Certificate", "UF_CRM_DOCDG": "Degree Certificate"}
for k, t in DOCK.items():
    FIELDS[k] = {"type": "boolean", "title": t, "formLabel": t}

# SEMX
SEMX = [("UF_CRM_1784188886587", 6), ("UF_CRM_1784188849279", 5), ("UF_CRM_1784188830072", 4)]
for k, n in SEMX: FIELDS[k] = enum("Semester %d Status" % n, PAY)
CONTACTS = {}
for i in range(1, 1101):
    cid = str(9000 + i)
    CONTACTS[cid] = {"ID": cid, "NAME": "Student%d" % i, "LAST_NAME": "Surname%d" % i,
                     "PHONE": [{"ID": "1", "VALUE_TYPE": "WORK", "VALUE": "+9198%08d" % i, "TYPE_ID": "PHONE"}],
                     "EMAIL": [{"ID": "2", "VALUE_TYPE": "WORK", "VALUE": "student%d@example.com" % i, "TYPE_ID": "EMAIL"}]}
CONTACTS["9005"]["NAME"] = 'Zoë "Quote" <b>Bold</b>'
CONTACTS["9006"]["LAST_NAME"] = "=HYPERLINK(1)"

DEALS = []
did = 100
for i in range(N):
    did += random.randint(1, 4)
    d = {"ID": str(did), "TITLE": "Deal %d" % did, "STAGE_ID": random.choice(STAGES)[0], "CATEGORY_ID": "46",
         "CONTACT_ID": str(9001 + i) if i < 1100 else (None if i % 2 else "0"),
         "DATE_CREATE": "2026-0%d-1%dT10:15:00+03:00" % (random.randint(1, 8), random.randint(0, 9)),
         "OPPORTUNITY": "%d.00" % random.choice([0, 45000, 90000, 120000]),
         "UF_CRM_1784702446": random.choice(UNI)[0], "UF_CRM_1784713245": random.choice(BATCH)[0],
         "UF_CRM_1784702837": random.choice(COURSE)[0], "UF_CRM_1784712099": random.choice(MODE)[0],
         "UF_CRM_1784697183": random.choice(SEMS)[0],
         "UF_CRM_1782985117": "2026-09-1%dT03:00:00+03:00" % random.randint(0, 9),
         "UF_CRM_1782985171": "" if i % 5 == 0 else "2026-07-0%dT03:00:00+03:00" % random.randint(1, 9),
         "UF_CRM_1773218485649": "1" if i % 3 == 0 else "0",
         "UF_CRM_1784701507": ""}
    hasref = (i % 3 == 0)
    adm = hasref and random.random() < 0.4
    if i % 47 == 0: adm = True
    d["UF_CRM_1782968155"] = "Y" if adm else "N"
    d["UF_CRM_REFLEAD"] = ("Lead %d" % (did + 5000)) if hasref and random.random() < 0.6 else ""
    d["UF_CRM_REFPAID"] = str(random.choice([1000, 2000, 5000])) if adm and random.random() < 0.7 else ""
    for k in DOCK:
        rr = random.random()
        d[k] = "Y" if rr < 0.65 else ("N" if rr < 0.95 else "")
    for k in SEM_KEYS:
        d[k] = random.choice(PAY)[0] if random.random() > 0.2 else ""
    for k, n in SEMX: d[k] = random.choice(PAY)[0] if random.random() > 0.3 else ""
    DEALS.append(d)
# BLANKUNI
for j in (10,11,12,13): DEALS[j]["UF_CRM_1784702446"] = ""
UNI.append(("14", "Delta & Sons <U> #1/2"))
FIELDS["UF_CRM_1784702446"] = enum("University Name", UNI)
for j in (20,21,22): DEALS[j]["UF_CRM_1784702446"] = "14"
# BLANKPROG
for j in (30,31): DEALS[j]["UF_CRM_1784702837"] = ""
# CMTEST
DEALS[40]["UF_CRM_1784701507"] = "Attending classes, UG MC final sem still pending to submit || 25/09/2026 08:50:49 am || Anita Singh\n------------------------------------------------------------------------\n"
DEALS[41]["UF_CRM_1784701507"] = """will complete the admission process | 29/09/2026 01:02:51 pm | Sahana A
completed Application | 28/09/2026 06:25:33 pm | Varsha M
dnp | 28/09/2026 11:34:21 am | Varsha M
counselled and shared details (smu) MBA ,completed 10th , diploma , and B.com with more than 50% , have all the documents , looking for this batch -telugu lead | 26/09/2026 07:37:55 pm | Varsha M
dnp | 09/09/2026 07:31:15 pm | Varsha M"""
DEALS[42]["UF_CRM_1784701507"] = "first call | 01/09/2026 10:00:00 am | A\nlater call | 12/09/2026 12:30:00 pm | B\nlatest call || 12/09/2026 04:05:00 pm || C\n---------\n"
DEALS[43]["UF_CRM_1784701507"] = "just a plain note without a date"
DEALS[44]["UF_CRM_1784701507"] = "line one\nline two | 30/09/2026 12:15:00 am | D\n----\nolder | 29/09/2026 11:59:59 pm | E"
DEALS[3]["STAGE_ID"] = "C46:GONE"          # a stage that no longer exists
for i in range(300):                          # decoys in other pipelines
    did += 1
    DEALS.append({"ID": str(did), "TITLE": "Other %d" % did, "STAGE_ID": "C0:NEW", "CATEGORY_ID": "0",
                  "CONTACT_ID": None, "UF_CRM_1784702446": "11"})

calls = {"n": 0}
UPDATES = []

for _i, _d in enumerate(DEALS):
    if _i % 7: _d["UF_CRM_1790840655148"] = ["91","92","93","94","91","92"][_i % 6]

def paged(items, start, select=None):
    total = len(items)
    chunk = items[start:start + 50]
    out = {"result": chunk, "total": total, "time": {"start": 0}}
    if start + 50 < total:
        out["next"] = start + 50
    return out

class H(BaseHTTPRequestHandler):
    def log_message(self, *a): pass
    def send(self, code, obj):
        b = json.dumps(obj).encode()
        self.send_response(code); self.send_header("Content-Type", "application/json")
        self.send_header("Content-Length", str(len(b))); self.end_headers(); self.wfile.write(b)
    def do_POST(self):
        n = int(self.headers.get("Content-Length", 0))
        body = json.loads(self.rfile.read(n) or b"{}")
        parts = self.path.strip("/").split("/")
        if parts[:3] != ["rest", "170", "TESTTOKEN"]:
            return self.send(401, {"error": "INVALID_CREDENTIALS", "error_description": "Invalid request credentials"})
        method = parts[3].replace(".json", "")
        calls["n"] += 1
        if calls["n"] % 11 == 0:
            return self.send(503, {"error": "QUERY_LIMIT_EXCEEDED", "error_description": "Too many requests"})
        start = int(body.get("start", 0))
        if method == "crm.deal.fields":
            return self.send(200, {"result": FIELDS})
        if method == "crm.status.list":
            ent = body.get("filter", {}).get("ENTITY_ID")
            rows = [{"ENTITY_ID": ent, "STATUS_ID": s, "NAME": nm, "SORT": so} for s, nm, so in STAGES] if ent == "DEAL_STAGE_46" else []
            return self.send(200, {"result": rows})
        if method == "crm.deal.get":
            d = next((x for x in DEALS if x["ID"] == str(body.get("id"))), None)
            if not d: return self.send(400, {"error": "NOT_FOUND", "error_description": "Not found"})
            full = {k: "" for k in FIELDS}; full.update(d)
            return self.send(200, {"result": full})
        if method == "crm.deal.update":
            d = next((x for x in DEALS if x["ID"] == str(body.get("id"))), None)
            if not d: return self.send(400, {"error": "NOT_FOUND", "error_description": "Not found"})
            if os.path.exists("/tmp/mock_readonly"): return self.send(403, {"error": "insufficient_scope", "error_description": "The request requires higher privileges than provided by the webhook token"})
            UPDATES.append(body)
            d.update(body.get("fields", {}))
            if body.get("fields", {}).get("STAGE_ID") == "C46:WON":   # pretend a robot writes a comment
                d["UF_CRM_1784701507"] = "auto moved to completed | 30/09/2026 09:00:00 am | Robot\n" + (d.get("UF_CRM_1784701507") or "")
            return self.send(200, {"result": True})
        if method == "crm.deal.list":
            if os.path.exists(FLAG_FAIL) and start >= 500:
                return self.send(400, {"error": "ACCESS_DENIED", "error_description": "Access denied (test)"})
            cat = str(body.get("filter", {}).get("CATEGORY_ID"))
            items = sorted([d for d in DEALS if d["CATEGORY_ID"] == cat], key=lambda d: int(d["ID"]))
            return self.send(200, paged(items, start))
        if method == "crm.contact.get":
            c = CONTACTS.get(str(body.get("id")))
            if not c: return self.send(400, {"error": "NOT_FOUND", "error_description": "Not found"})
            return self.send(200, {"result": c})
        if method == "crm.contact.update":
            c = CONTACTS.get(str(body.get("id")))
            if not c: return self.send(400, {"error": "NOT_FOUND", "error_description": "Not found"})
            UPDATES.append(body)
            for k, v in body.get("fields", {}).items():
                if k in ("PHONE", "EMAIL"):
                    cur = c.get(k) or []
                    nxt = []
                    for it in v:
                        if it.get("ID"):
                            old = next((x for x in cur if str(x.get("ID")) == str(it["ID"])), None)
                            if old is not None and it.get("VALUE", "") != "": nxt.append({**old, "VALUE": it["VALUE"]})
                        elif it.get("VALUE"): nxt.append({"ID": str(900000 + len(nxt)), "VALUE": it["VALUE"], "VALUE_TYPE": it.get("VALUE_TYPE", "WORK")})
                    c[k] = nxt
                else: c[k] = v
            return self.send(200, {"result": True})
        if method == "crm.contact.add":
            nid = str(max(int(k) for k in CONTACTS) + 1)
            f = body.get("fields", {})
            c = {"ID": nid, "NAME": f.get("NAME", ""), "LAST_NAME": f.get("LAST_NAME", ""), "PHONE": [], "EMAIL": []}
            for k in ("PHONE", "EMAIL"):
                c[k] = [{"ID": str(800000 + len(c[k]) + int(nid)), "VALUE": it["VALUE"], "VALUE_TYPE": "WORK"} for it in f.get(k, []) if it.get("VALUE")]
            CONTACTS[nid] = c; UPDATES.append(body)
            return self.send(200, {"result": int(nid)})
        if method == "crm.deal.contact.add":
            d = next((x for x in DEALS if x["ID"] == str(body.get("id"))), None)
            d["CONTACT_ID"] = str(body["fields"]["CONTACT_ID"]); UPDATES.append(body)
            return self.send(200, {"result": True})
        if method == "crm.contact.list" and ("PHONE" in body.get("filter", {}) or "EMAIL" in body.get("filter", {})):
            fl = body["filter"]; k = "PHONE" if "PHONE" in fl else "EMAIL"
            return self.send(200, {"result": [c for c in CONTACTS.values() if any(x["VALUE"] == fl[k] for x in (c.get(k) or []))], "total": 0})
        if method == "crm.contact.list":
            ids = [str(x) for x in body.get("filter", {}).get("ID", [])]
            items = [CONTACTS[i] for i in ids if i in CONTACTS]
            return self.send(200, paged(items, start))
        return self.send(400, {"error": "ERROR_METHOD_NOT_FOUND", "error_description": "Method not found"})

FIELDS["UF_CRM_1780040071743"] = {"type": "string", "title": "Roll Number", "formLabel": "Roll Number"}
FIELDS["UF_CRM_1790840655148"] = enum("Documents Status", [("91", "Complete"), ("92", "Pending"), ("93", "Partially Received"), ("94", "Not Received")])
FIELDS["UF_CRM_1790246879"] = enum("Team Name", [("71", "Team A"), ("72", "Team B")])
FIELDS["UF_CRM_1784699787"] = enum("Lead Status", [("81", "Hot"), ("82", "Warm"), ("83", "Cold")])
FIELDS["UF_CRM_1773208334206"] = {"type": "date", "title": "Admission Date", "formLabel": "Admission Date"}
FIELDS["UF_CRM_1780039753"] = {"type": "date", "title": "Registration Date", "formLabel": "Registration Date"}
FIELDS["UF_CRM_1784722248"] = {"type": "datetime", "title": "Deal Next Follow up [Update-G]", "formLabel": "Deal Next Follow up [Update-G]"}
FIELDS["UF_CRM_1784722278"] = {"type": "datetime", "title": "Deal Next Follow up [View-G]", "formLabel": "Deal Next Follow up [View-G]"}
FIELDS["UF_CRM_1782985117"]["title"] = FIELDS["UF_CRM_1782985117"]["formLabel"] = "Next Payment Date (P)"
FIELDS["UF_CRM_1782985171"]["title"] = FIELDS["UF_CRM_1782985171"]["formLabel"] = "Last Payment Date (P)"
for _d in DEALS[:60]:
    _d["UF_CRM_1790246879"] = "71"; _d["UF_CRM_1784699787"] = "81"; _d["UF_CRM_1784722278"] = "2026-10-05T11:00:00+05:30"; _d["UF_CRM_1784722248"] = "2026-10-05T11:00:00+05:30"
# same-name decoys that exist in the real portal and must NOT be picked up
FIELDS["UF_CRM_699C656E98946"] = enum("University", [("1038", "Amity"), ("1040", "Andhra")])
FIELDS["UF_CRM_1774342388428"] = {"type": "string", "title": "University name", "formLabel": "University name"}
FIELDS["UF_CRM_1773206573790"] = {"type": "string", "title": "Team", "formLabel": "Team"}
FIELDS["UF_CRM_1774521696"] = enum("Program Name", [("1548", "MBA")])
FIELDS["UF_CRM_1782906841"] = {"type": "string", "title": "Batch", "formLabel": "Batch"}
for _d in DEALS[:30]:
    _d["UF_CRM_699C656E98946"] = "1038"; _d["UF_CRM_1773206573790"] = "old team"; _d["UF_CRM_1782906841"] = "old batch"
ThreadingHTTPServer(("127.0.0.1", PORT), H).serve_forever()
