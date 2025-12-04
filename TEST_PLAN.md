# Wooon.nl MVP Test Plan

## Overview

This document outlines the comprehensive test plan for the Wooon.nl MVP (Frontend + Consumer Accounts only).

**Scope:** Frontend functionality, consumer authentication, account management, property browsing
**Out of Scope:** Admin panel, realtor features, API integrations, scraping functionality

---

## 1. Authentication

### 1.1 Registration

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Successful registration | Fill all required fields, submit | User created, redirected to email verification page |
| Registration with existing email | Use already registered email | Error: "E-mailadres is al in gebruik" |
| Registration without required fields | Submit with empty email/password | Validation errors shown |
| Password mismatch | Enter different passwords | Error: "Wachtwoorden komen niet overeen" |
| Weak password | Enter password < 8 chars | Validation error for password strength |
| Optional fields | Register with only required fields | Success, optional fields nullable |
| All fields filled | Fill all fields including address | All data saved correctly |

### 1.2 Login

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Successful login | Enter valid credentials | Redirected to account page |
| Invalid email | Enter non-existent email | Error: "Ongeldige inloggegevens" |
| Invalid password | Enter wrong password | Error: "Ongeldige inloggegevens" |
| Empty fields | Submit without credentials | Validation errors |
| Remember me checked | Login with "Onthoud mij" | Session persists after browser close |
| Remember me unchecked | Login without "Onthoud mij" | Session expires on browser close |
| Redirect after login | Access protected page while logged out | After login, redirect to originally requested page |

### 1.3 Logout

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Logout from header | Click "Uitloggen" in header | Session ended, redirected to homepage |
| Logout from account page | Click "Uitloggen" button | Session ended, redirected to homepage |
| Access protected page after logout | Try /account after logout | Redirected to login page |

### 1.4 Password Reset

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Request reset - valid email | Enter registered email | Success message shown, email sent |
| Request reset - invalid email | Enter non-existent email | Same success message (security) |
| Request reset - empty email | Submit without email | Validation error |
| Reset link - valid token | Click link within 60 min | Reset form displayed |
| Reset link - expired token | Click link after 60 min | Error: "Link is verlopen" |
| Reset link - invalid token | Modify token in URL | Error: "Ongeldige link" |
| Reset - password mismatch | Enter different passwords | Validation error |
| Reset - successful | Enter matching valid passwords | Password changed, redirected to login |
| Login after reset | Use new password | Successful login |
| Login with old password | Use old password after reset | Login fails |

### 1.5 Email Verification

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Verification page shown | After registration | Verification notice page displayed |
| Verification banner | Navigate site while unverified | Yellow banner shown on all pages |
| Resend verification | Click "Verstuur opnieuw" | New email sent, success message |
| Resend rate limit | Click resend multiple times | Rate limited after several attempts |
| Valid verification link | Click link in email | Email verified, banner removed |
| Expired verification link | Click old link | Error message, option to resend |
| Invalid verification link | Modify link | Error message |
| Already verified | Click link again | Appropriate message |

---

## 2. Account Management

### 2.1 User Profile (Mijn gegevens)

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| View profile | Navigate to account page | All user data displayed correctly |
| Update first name | Change and save | Updated successfully |
| Update last name | Change and save | Updated successfully |
| Update phone | Change and save | Updated successfully |
| Update email | Not allowed | Email field readonly or change requires verification |
| Empty required fields | Clear first/last name, save | Validation error |
| Cancel changes | Make changes, click "Annuleren" | Changes reverted |
| Save success message | Save valid changes | Success toast/message shown |

### 2.2 Search Profiles (Zoekprofielen)

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| View empty state | No profiles exist | "Je hebt nog geen zoekprofielen" message |
| Create profile - minimal | Only fill name | Profile created with name only |
| Create profile - full | Fill all fields | Profile created with all criteria |
| Create profile - required name | Submit without name | Validation error |
| Create 5 profiles | Create maximum allowed | All 5 created successfully |
| Create 6th profile | Try to create when 5 exist | Error: "Maximaal 5 zoekprofielen" |
| Edit profile | Click edit, modify, save | Changes saved |
| Delete profile | Click delete, confirm | Profile removed |
| Delete cancel | Click delete, cancel confirmation | Profile retained |
| Toggle active/inactive | Click toggle switch | Status changes, visual feedback |
| Cities as comma-separated | Enter "Amsterdam, Utrecht" | Stored as array, displayed correctly |
| Price range validation | Min > Max | Validation error or swap values |
| Surface range validation | Min > Max | Validation error or swap values |
| Profile card display | View profile in list | All criteria shown as chips/badges |

### 2.3 Favorites (Favorieten)

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| View empty state | No favorites | "Je hebt nog geen favoriete woningen" message |
| Add favorite (from detail) | Click heart on property detail | Property added to favorites |
| Add favorite (from list) | Click heart on property card | Property added to favorites |
| Remove favorite | Click filled heart | Property removed from favorites |
| View favorites list | Navigate to favorites section | All favorited properties shown |
| Favorite count in sidebar | Add/remove favorites | Count updates in navigation |
| Favorite requires auth | Click heart while logged out | Redirect to login |
| Favorite requires verified email | Click heart while unverified | Error or prompt to verify |

### 2.4 Notification Settings (Notificaties)

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| View current settings | Navigate to notifications | Current preferences shown |
| Toggle new properties | Switch on/off | State changes |
| Toggle price changes | Switch on/off | State changes |
| Toggle newsletter | Switch on/off | State changes |
| Toggle marketing | Switch on/off | State changes |
| Save settings | Click "Opslaan" | Preferences saved, success message |
| Settings persist | Reload page | Saved settings displayed |
| Default values | New user | notify_new_properties=true, notify_price_changes=true, others=false |

---

## 3. Property Browsing

### 3.1 Homepage

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Page loads | Navigate to / | Homepage displays correctly |
| Hero search | Enter location, select type, search | Redirected to /zoeken with filters |
| Featured properties | Properties exist in DB | Featured section shows properties |
| Featured empty | No properties | Appropriate empty state |
| Category links | Click Nieuwbouw/Huur/Koop | Navigate to search with filter |
| Mortgage calculator link | Click calculator card | Navigate to /maandlasten |

### 3.2 Search Page

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Page loads | Navigate to /zoeken | Search page with filters displayed |
| Search by location | Enter city name | Results filtered by location |
| Filter by type - Koop | Select "Koop" | Only sale properties shown |
| Filter by type - Huur | Select "Huur" | Only rental properties shown |
| Filter by price range | Set min/max price | Results within range |
| Filter by surface | Set min/max m² | Results within range |
| Filter by rooms | Select "3+ kamers" | Properties with 3+ rooms |
| Filter by energy label | Select "A" | Properties with A or better |
| Combine filters | Apply multiple filters | Results match all criteria |
| Clear single filter | Click X on filter chip | That filter removed, others remain |
| Clear all filters | Click "Alles wissen" | All filters cleared |
| Sort by newest | Select "Nieuwste eerst" | Most recent first |
| Sort by price | Select price sort | Ordered by price |
| Sort by surface | Select surface sort | Ordered by surface area |
| Pagination | More than page size results | Pagination controls shown |
| Navigate pages | Click page 2 | Second page of results |
| URL reflects filters | Apply filters | URL query params updated |
| Direct URL with filters | Visit /zoeken?type=sale | Filters applied from URL |
| Browser back/forward | Use browser navigation | Filters restored correctly |
| No results | Apply impossible filter combo | "Geen woningen gevonden" message |
| Loading state | Apply filter | Spinner shown during fetch |

### 3.3 Property Detail Page

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Page loads | Click property card | Detail page displayed |
| Property images | Property has images | Gallery/carousel shown |
| Property specs | View details | All specs displayed (rooms, surface, etc.) |
| Price displayed | View price section | Correct price with formatting |
| Description | View description | Full property description |
| Location/map | View location | Address shown (map if implemented) |
| Agent contact - with data | Agent info exists | Name, phone, email displayed |
| Agent contact - phone link | Click phone number | tel: link works |
| Agent contact - email link | Click email | mailto: link works |
| Agent contact - no data | No agent info | Section hidden or "Niet beschikbaar" |
| Favorite button | Click heart | Toggle favorite state |
| Share functionality | If implemented | Share options work |
| Back to search | Click back | Returns to search with filters preserved |
| 404 for invalid ID | Visit /woning/999999 | 404 page shown |

---

## 4. Static Pages

### 4.1 Privacy Policy (/privacy)

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Page loads | Navigate to /privacy | Privacy policy displayed |
| Content present | View page | Dutch privacy policy text |
| Navigation works | Use header/footer | Can navigate away |

### 4.2 Terms & Conditions (/voorwaarden)

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Page loads | Navigate to /voorwaarden | Terms displayed |
| Content present | View page | Dutch terms text |
| Privacy link | Click privacy link in content | Navigates to privacy page |

### 4.3 About Page (/over-wooon)

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Page loads | Navigate to /over-wooon | About page displayed |
| Content sections | View page | Mission, features, CTA shown |
| Register CTA | Click register button | Navigates to registration |

### 4.4 Contact Page (/contact)

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Page loads | Navigate to /contact | Contact page displayed |
| Email displayed | View contact info | Email address shown |
| Phone displayed | View contact info | Phone number shown |
| Address displayed | View contact info | Physical address shown |
| FAQ section | View FAQ | Common questions displayed |

---

## 5. Navigation & Layout

### 5.1 Header

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Logo link | Click Wooon.nl logo | Navigate to homepage |
| Koop link | Click "Koop" | Navigate to /zoeken?type=sale |
| Huur link | Click "Huur" | Navigate to /zoeken?type=rent |
| Nieuwbouw link | Click "Nieuwbouw" | Navigate appropriately |
| Maandlasten link | Click "Maandlasten" | Navigate to /maandlasten |
| Login link (logged out) | View header | "Inloggen" shown |
| Register link (logged out) | View header | "Account aanmaken" shown |
| Account link (logged in) | View header | "Mijn account" shown |
| Logout button (logged in) | View header | "Uitloggen" shown |

### 5.2 Mobile Navigation

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Hamburger visible | View on mobile (<768px) | Hamburger icon shown |
| Desktop nav hidden | View on mobile | Nav links hidden |
| Open menu | Click hamburger | Menu slides down |
| Close menu | Click hamburger again | Menu slides up |
| Close on link click | Click nav link | Menu closes, navigates |
| All links present | Open menu | All nav items visible |
| Auth state correct | View menu logged in/out | Correct links shown |

### 5.3 Footer

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Footer visible | Scroll to bottom | Footer displayed |
| Over Wooon link | Click link | Navigate to /over-wooon |
| Contact link | Click link | Navigate to /contact |
| Privacy link | Click link | Navigate to /privacy |
| Voorwaarden link | Click link | Navigate to /voorwaarden |
| Makelaars link | Click link | Navigate to makelaar dashboard |
| Copyright text | View footer | Current year shown |

### 5.4 Cookie Consent Banner

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Banner shows | First visit (clear localStorage) | Banner visible at bottom |
| Accept cookies | Click "Accepteren" | Banner hides, consent stored |
| Banner hidden after accept | Reload page | Banner not shown |
| Privacy link | Click in banner | Navigate to privacy page |
| Consent persists | Clear cookies, revisit | Banner shows again |

---

## 6. Error Handling

### 6.1 404 Page

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Page displays | Visit /non-existent-url | Custom 404 page shown |
| Search box | View 404 page | Search input available |
| Homepage link | Click "Naar homepage" | Navigate to / |
| Search link | Click "Alle woningen" | Navigate to /zoeken |
| Styling | View page | Matches site design |

### 6.2 500 Page

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Page displays | Trigger server error | Custom 500 page shown |
| Homepage link | Click link | Navigate to / |
| Styling | View page | Matches site design |
| No sensitive info | View page | No stack traces/debug info |

### 6.3 503 Page (Maintenance)

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Page displays | Enable maintenance mode | Custom 503 page shown |
| Message | View page | Maintenance message in Dutch |

---

## 7. Security

### 7.1 Rate Limiting

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Login rate limit | 6 failed logins in 1 minute | 429 Too Many Requests |
| Register rate limit | 6 registrations in 1 minute | 429 Too Many Requests |
| Password reset rate limit | 6 requests in 1 minute | 429 Too Many Requests |
| Rate limit message | Trigger rate limit | User-friendly Dutch message |
| Rate limit resets | Wait 1 minute | Can try again |

### 7.2 CSRF Protection

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Forms have token | Inspect form HTML | _token field present |
| Invalid token rejected | Submit with invalid token | 419 error |
| AJAX has token | Inspect network requests | X-CSRF-TOKEN header sent |

### 7.3 Authentication Protection

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Protected routes | Access /account logged out | Redirect to login |
| API endpoints | Access without auth | 401 Unauthorized |
| Other user's data | Try to access other's profile | 403 Forbidden |

### 7.4 Input Validation

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| XSS in search | Enter `<script>alert(1)</script>` | Escaped, no execution |
| XSS in profile | Enter script in name field | Escaped on display |
| SQL injection | Enter `'; DROP TABLE users;--` | No SQL error, input sanitized |

---

## 8. SEO & Meta Tags

### 8.1 Meta Tags

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Homepage title | View page source | "Wooon.nl - Vind je ideale woning" |
| Homepage description | View meta description | Appropriate description |
| Search page title | View /zoeken source | "Woningen zoeken - Wooon.nl" |
| Property detail title | View property page | Property address in title |
| OG tags present | View page source | og:title, og:description, og:image |
| Property OG image | Share property link | Property image shown in preview |

### 8.2 Structured Data

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Homepage schema | Check with validator | Valid structured data |
| Property schema | Check property page | RealEstateListing schema |

---

## 9. Performance

### 9.1 Page Load

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Homepage load time | Measure load time | < 3 seconds |
| Search page load | Measure with results | < 3 seconds |
| Property detail load | Measure load time | < 3 seconds |
| Image optimization | Check image sizes | Appropriately sized |

### 9.2 AJAX Operations

| Test Case | Steps | Expected Result |
|-----------|-------|-----------------|
| Search filter response | Apply filter | < 1 second response |
| Favorite toggle | Click heart | Instant feedback |
| Profile save | Save changes | < 2 seconds |

---

## 10. Browser Compatibility

| Browser | Version | Status |
|---------|---------|--------|
| Chrome | Latest | To test |
| Firefox | Latest | To test |
| Safari | Latest | To test |
| Edge | Latest | To test |
| Mobile Safari | iOS 15+ | To test |
| Mobile Chrome | Android | To test |

---

## 11. Responsive Design

| Breakpoint | Width | Items to Check |
|------------|-------|----------------|
| Mobile | < 640px | Hamburger menu, stacked layout, touch targets |
| Tablet | 640-1024px | Two-column layouts, readable text |
| Desktop | > 1024px | Full navigation, multi-column grids |

---

## Test Execution Tracking

### Authentication
- [ ] 1.1 Registration (7 cases)
- [ ] 1.2 Login (7 cases)
- [ ] 1.3 Logout (3 cases)
- [ ] 1.4 Password Reset (10 cases)
- [ ] 1.5 Email Verification (8 cases)

### Account Management
- [ ] 2.1 User Profile (8 cases)
- [ ] 2.2 Search Profiles (14 cases)
- [ ] 2.3 Favorites (8 cases)
- [ ] 2.4 Notification Settings (7 cases)

### Property Browsing
- [ ] 3.1 Homepage (6 cases)
- [ ] 3.2 Search Page (21 cases)
- [ ] 3.3 Property Detail (14 cases)

### Static Pages
- [ ] 4.1 Privacy (3 cases)
- [ ] 4.2 Terms (3 cases)
- [ ] 4.3 About (3 cases)
- [ ] 4.4 Contact (5 cases)

### Navigation & Layout
- [ ] 5.1 Header (9 cases)
- [ ] 5.2 Mobile Navigation (7 cases)
- [ ] 5.3 Footer (7 cases)
- [ ] 5.4 Cookie Banner (5 cases)

### Error Handling
- [ ] 6.1 404 Page (5 cases)
- [ ] 6.2 500 Page (4 cases)
- [ ] 6.3 503 Page (2 cases)

### Security
- [ ] 7.1 Rate Limiting (5 cases)
- [ ] 7.2 CSRF Protection (3 cases)
- [ ] 7.3 Auth Protection (3 cases)
- [ ] 7.4 Input Validation (3 cases)

### SEO
- [ ] 8.1 Meta Tags (6 cases)
- [ ] 8.2 Structured Data (2 cases)

### Performance
- [ ] 9.1 Page Load (4 cases)
- [ ] 9.2 AJAX Operations (3 cases)

### Browser Compatibility
- [ ] Chrome
- [ ] Firefox
- [ ] Safari
- [ ] Edge
- [ ] Mobile Safari
- [ ] Mobile Chrome

### Responsive Design
- [ ] Mobile (< 640px)
- [ ] Tablet (640-1024px)
- [ ] Desktop (> 1024px)

---

## Test Data Requirements

### Users
1. Unverified user account
2. Verified user account
3. User with 5 search profiles (max limit)
4. User with favorites

### Properties
1. Sale property with all fields
2. Rental property with all fields
3. Property with agent contact info
4. Property without agent contact info
5. Property with multiple images
6. Properties in different cities for search testing

---

## Notes

- All text should be in Dutch
- Test with both light theme (default)
- Clear localStorage/cookies between test sessions as needed
- Use incognito/private mode for fresh session tests
