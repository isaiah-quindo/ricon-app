# Group Registration — Test Data

Copy-paste fixtures for `/register/group`. Five participants across **both** active
categories, so this also exercises the mixed-category path.

**Heads up:** submitting this writes real rows to Supabase and a real file to the S3
bucket. Cleanup query is at the bottom.

---

## Expected result

| | |
|---|---|
| Participants | 5 |
| 3 × TGC 100 @ ₱7,000 | ₱21,000 |
| 2 × TGC60 @ ₱5,000 | ₱10,000 |
| **Subtotal** | **₱31,000.00** |
| Group discount (5%) | −₱1,550.00 |
| **Total Due** | **₱29,450.00** |

Each TGC 100 entry should show `price_paid` ₱6,650.00, each TGC60 entry ₱4,750.00.
There should be **no discount code field** anywhere on the page.

---

## Organizer

Not one of the runners — that is the point of the block.

```
Full Name:   Marites Bautista
Email:       marites.bautista@baguiotrail.example
Mobile:      +63 917 100 2001
Team / Club: Baguio Trail Club
```

Payment Method: `Bank Transfer`
Proof of payment: any JPG or PNG under 5MB.

---

## Participant 1 — TGC 100

```
First Name:       Juan
Last Name:        Dela Cruz
Sex:              Male
Birthdate:        03/14/1988          (stored as 1988-03-14)
Email:            juan.delacruz@example.com
Mobile Number:    +63 917 201 3001
Nationality:      Filipino
Team/Affiliation: Baguio Trail Club
Home Address:     12 Leonard Wood Road, Barangay Gibraltar, Baguio City, Benguet, Philippines
Shirt Size:       M
Race Category:    TGC 100
Emergency Name:   Rosa Dela Cruz
Emergency Number: +63 918 201 3001
```

## Participant 2 — TGC 100

```
First Name:       Maria
Last Name:        Santos
Sex:              Female
Birthdate:        07/22/1992          (stored as 1992-07-22)
Email:            maria.santos@example.com
Mobile Number:    +63 917 202 3002
Nationality:      Filipino
Team/Affiliation: Baguio Trail Club
Home Address:     45 Session Road, Barangay Central Business District, Baguio City, Benguet, Philippines
Shirt Size:       S
Race Category:    TGC 100
Emergency Name:   Pedro Santos
Emergency Number: +63 918 202 3002
```

## Participant 3 — TGC 100

```
First Name:       Andres
Last Name:        Lim
Sex:              Male
Birthdate:        11/05/1985          (stored as 1985-11-05)
Email:            andres.lim@example.com
Mobile Number:    +63 917 203 3003
Nationality:      Filipino
Team/Affiliation: Baguio Trail Club
Home Address:     8 Military Cut-off Road, Barangay Military Cut-off, Baguio City, Benguet, Philippines
Shirt Size:       L
Race Category:    TGC 100
Emergency Name:   Carmen Lim
Emergency Number: +63 918 203 3003
```

## Participant 4 — TGC60

```
First Name:       Grace
Last Name:        Villanueva
Sex:              Female
Birthdate:        01/30/1995          (stored as 1995-01-30)
Email:            grace.villanueva@example.com
Mobile Number:    +63 917 204 3004
Nationality:      Filipino
Team/Affiliation: Baguio Trail Club
Home Address:     27 Bokawkan Road, Barangay Rock Quarry, Baguio City, Benguet, Philippines
Shirt Size:       XS
Race Category:    TGC60
Emergency Name:   Luis Villanueva
Emergency Number: +63 918 204 3004
```

## Participant 5 — TGC60

```
First Name:       Tomas
Last Name:        Aguinaldo
Sex:              Male
Birthdate:        09/09/1979          (stored as 1979-09-09)
Email:            tomas.aguinaldo@example.com
Mobile Number:    +63 917 205 3005
Nationality:      Filipino
Team/Affiliation: Baguio Trail Club
Home Address:     3 Marcos Highway, Barangay Camp 7, Baguio City, Benguet, Philippines
Shirt Size:       XL
Race Category:    TGC60
Emergency Name:   Elena Aguinaldo
Emergency Number: +63 918 205 3005
```

---

## Optional participants 6-10

Add these to cross the 10-person threshold and watch the discount jump to 10%.
All TGC60, so subtotal becomes ₱31,000 + ₱25,000 = **₱56,000**, less 10% = **₱50,400.00**.

| # | First | Last | Sex | Birthdate | Email | Mobile | Shirt |
|---|-------|------|-----|-----------|-------|--------|-------|
| 6 | Ramon | Castillo | Male | 04/18/1990 | ramon.castillo@example.com | +63 917 206 3006 | M |
| 7 | Liza | Mendoza | Female | 12/02/1993 | liza.mendoza@example.com | +63 917 207 3007 | S |
| 8 | Paolo | Reyes | Male | 06/25/1987 | paolo.reyes@example.com | +63 917 208 3008 | L |
| 9 | Ana | Bituin | Female | 02/11/1996 | ana.bituin@example.com | +63 917 209 3009 | M |
| 10 | Nestor | Gaddi | Male | 08/07/1982 | nestor.gaddi@example.com | +63 917 210 3010 | 2XL |

Same address, nationality, team and emergency contact pattern as above.

---

## Field notes

- **Birthdate** renders as `mm/dd/yyyy` in the browser, so type it that way.
- **Nationality** is a searchable combobox: type `Fili` and click *Filipino*.
- **Team / Affiliation** on participant 1 has an *Apply to everyone* link, as does
  **Home Address** — quicker than typing all five.
- **Home Address** is capped at 255 characters.
- The **remove** button is hidden at exactly 5 participants and appears above that.

---

## Cleanup after testing

Removes every grouped registration, its proofs, the group rows and the uploaded S3
objects. Leaves your 7 real registrations alone — they have a null group.

```bash
php artisan tinker --execute="
\$ids = \App\Models\Registration::whereNotNull('registration_group_id')->pluck('id');
\$paths = \App\Models\PaymentProof::whereIn('registration_id', \$ids)->pluck('image_path')->unique();
foreach (\$paths as \$p) { Storage::disk('s3')->delete(\$p); }
\App\Models\PaymentProof::whereIn('registration_id', \$ids)->delete();
\App\Models\Registration::whereIn('id', \$ids)->delete();
\App\Models\RegistrationGroup::query()->delete();
echo 'registrations: ' . \App\Models\Registration::count() . ' (should be 7)' . PHP_EOL;
echo 'groups: ' . \App\Models\RegistrationGroup::count() . ' (should be 0)' . PHP_EOL;
echo 's3 proofs: ' . count(Storage::disk('s3')->files('payment_proofs')) . ' (should be 256)' . PHP_EOL;
"
```
