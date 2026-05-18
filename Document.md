# W3P (Alliance WebPOS Third-Party Protocol) Integration — QA Document for Alliance

## 1. Overview

We (Doxo Injoy / Praxxys) are integrating our system with Alliance WebPOS using the **W3P SOAP protocol** to synchronize loyalty data between our platforms.

This document summarizes what we have tested, what we need, and the technical requirements to proceed.

---

## 2. What We Have Tested

| Test | Result |
|------|--------|
| **WSDL URL** | ✅ `http://statara2.alliancewebpos.net/appserv/app/w3p/w3p.wsdl` — accessible and valid |
| **SOAP Endpoint** | ✅ `http://app.alliancewebpos.net/appserv/soap/server.php` — responds HTTP 200 |
| **WSDL Definition** | ✅ Single operation `call(action: string, params: anyType) -> string` |
| **SOAPAction** | `urn:localhost-main#call` |
| **PHP SoapClient** | ✅ Successfully connects, sends requests, and receives responses |
| **Server Stack** | Apache, PHP 7.4.33 |

### Actions Found on Server (require authentication)

| Action | Status |
|--------|--------|
| `GET_ACCOUNT` | ✅ Recognized — returns "Not logon or session has expired" |
| `GET_PRODUCT` | ✅ Recognized — returns "Not logon or session has expired" |
| `GET_TRANSACTION` | ✅ Recognized — returns "Not logon or session has expired" |

### Actions NOT Available

| Tested Action | Result |
|---------------|--------|
| `GET_LOYALTY_MEMBER` | ❌ "Action not supported" |
| `GET_LOYALTY_LEDGER` | ❌ "Action not supported" |
| `GET_LOYALTY_POINT_BALANCE` | ❌ "Action not supported" |
| `GET_LOYALTY_POINT_USAGE` | ❌ "Action not supported" |
| `GET_LOYALTY_TRANSACTION_SUMMARY` | ❌ "Action not supported" |
| `GET_LOYALTY_ADJUST_POINT` | ❌ "Action not supported" |
| `SAVE_LOYALTY_ADJUST_POINT` | ❌ "Action not supported" |
| 25+ other variations (MEMBER_GET, etc.) | ❌ "Action not supported" |

---

## 3. What We Need From Alliance

### 3.1 W3P Credentials

Per the W3P protocol documentation:

> *"3rd party must request Alliance to register the users before any exchange can take place. In return, they will receive an ID (w3p_id) and access key (w3p_key)."*

We need:

| Item | Purpose |
|------|---------|
| **w3p_id** | Unique identifier for our integration account |
| **w3p_key** | Authentication key for all SOAP requests |

**Environment:** `statara2.alliancewebpos.net`

### 3.2 Loyalty SOAP Actions

The W3P documentation references the following loyalty actions that we need enabled on our server:

| Action | Purpose |
|--------|---------|
| `GET_LOYALTY_MEMBER` | Look up loyalty member by FSP member ID or account |
| `GET_LOYALTY_POINT_BALANCE` | Retrieve current point balance for a member |
| `GET_LOYALTY_POINT_USAGE` | Get earned/redeemed/expired point breakdown |
| `GET_LOYALTY_TRANSACTION_SUMMARY` | Get transaction history for a member |
| `GET_LOYALTY_LEDGER` | Get point ledger entries |
| `GET_LOYALTY_ADJUST_POINT` | Retrieve point adjustment records |
| `SAVE_LOYALTY_ADJUST_POINT` | Submit point adjustments from our system |

### 3.3 Test Data

We have the following test data — please confirm these are valid for the `statara2` environment:

| Item | Value |
|------|-------|
| **FSP Member ID** | `[REDACTED]` |
| **Branch** | `[REDACTED]` |
| **Dashboard credentials** | `[REDACTED]` / `[REDACTED]` |

---

## 4. Integration Architecture (Our Side)

```
Doxo Ingredients Admin (Laravel 12)
         ↓
W3P SOAP Client (PHP SoapClient)
         ↓  HTTP SOAP 1.1
    ┌───────┴───────┐
    │  Alliance      │
    │  WebPOS W3P   │
    │  (statara2)   │
    └───────────────┘
```

### Planned Implementation

| Component | Description |
|-----------|-------------|
| `W3PSoapClient` | Core SOAP client handling connection, XML payload building, response parsing, error handling |
| `W3PLoyaltyService` | All 7 loyalty actions — builds XML, calls SOAP, parses responses |
| `W3PSyncService` | Orchestrates sync between Alliance loyalty data and our CustomerLoyalty models |
| Queued Jobs | Background jobs for scheduled sync (member lookup, point balance updates) |
| Admin UI | Manual "Sync from Alliance" buttons in admin panel |

### Data Flow

```
GET_LOYALTY_MEMBER  ──►  CustomerLoyalty.w3p_fspmembid
GET_LOYALTY_POINT_BALANCE  ──►  CustomerLoyalty.loyalty_points
SAVE_LOYALTY_ADJUST_POINT  ──►  Submit point adjustments to Alliance
```

---

## 5. Request to Alliance

1. **Enable the following W3P SOAP actions** on `statara2.alliancewebpos.net`:
   - `GET_LOYALTY_MEMBER`
   - `GET_LOYALTY_LEDGER`
   - `GET_LOYALTY_POINT_BALANCE`
   - `GET_LOYALTY_POINT_USAGE`
   - `GET_LOYALTY_TRANSACTION_SUMMARY`
   - `GET_LOYALTY_ADJUST_POINT`
   - `SAVE_LOYALTY_ADJUST_POINT`

2. **Provide valid W3P credentials** (`w3p_id` and `w3p_key`) for the `statara2` environment

3. **Confirm test data** — verify that FSP member ID `[REDACTED]` is valid for testing

---

## 6. Contact

| Role | Contact |
|------|---------|
| Integration Team | [REDACTED] |
| System | statara2.alliancewebpos.net |

---

REFERENCE: https://drive.google.com/file/d/1RkBO9xhU6ZXrV6CJf9P067eEYkiIiMMl/view?usp=drive_link

*Document prepared: May 18, 2026*
