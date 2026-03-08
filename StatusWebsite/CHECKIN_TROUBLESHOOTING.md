# Troubleshooting Guide: Catat Masuk (Check-In) Not Working

## Issues Fixed

### 1. **Request Validation Method** [FIXED]
- **Problem**: Using `$request->` directly instead of validated array
- **Fix**: Changed to use `$validated = $request->validate(...)` and pass `$validated` to closure

### 2. **Status Pembayaran Default Value** [FIXED]
- **Problem**: Setting `status_pembayaran` to `null` instead of proper enum value
- **Fix**: Changed to `'status_pembayaran' => 'pending'` to match database enum constraint

### 3. **Catatan Field Handling** [FIXED]
- **Problem**: Passing `$request->catatan` directly without null check
- **Fix**: Using `$validated['catatan'] ?? null` for proper null handling

### 4. **Error Logging** [FIXED]
- **Problem**: Errors not being logged for debugging
- **Fix**: Added `\Log::error()` to capture stack trace

## Diagnostic Steps

### Step 1: Check Data Availability
```bash
php artisan diagnose:checkin
```

If data is missing, seed test data:
```bash
php artisan seed:checkin-data
```

### Step 2: Check Database Constraints
Make sure your `tb_transaksi` table has:
- `status_pembayaran` enum with values: `'pending'`, `'berhasil'`, `'gagal'`
- Foreign keys for: `id_kendaraan`, `id_tarif`, `id_area`, `id_user`
- All required fields: `id_kendaraan`, `id_tarif`, `id_area`, `id_user`, `waktu_masuk`, `status`

### Step 3: Verify Models
Check that models have correct:
- `$primaryKey` values
- `$fillable` arrays include all fields
- Foreign key relationships
- Soft delete traits (if enabled)

### Step 4: Check Logs
```bash
tail -f storage/logs/laravel.log
```

Look for "CheckIn Error:" messages with stack traces.

## Common Issues and Solutions

### Issue: "Kapasitas area parkir sudah penuh"
- Check the `terisi` value in `tb_area_parkir`
- Verify that area capacity is correctly set
- Reset terisi count: `UPDATE tb_area_parkir SET terisi = 0;`

### Issue: Validation errors for non-existent IDs
- Ensure vehicles, tariffs, and parking areas exist in database
- Run: `php artisan seed:checkin-data`
- Check that selected items have valid IDs

### Issue: Form not submitting
- Check browser console for JavaScript errors
- Verify CSRF token in form
- Ensure route `transaksi.checkIn` is defined

### Issue: Redirects to wrong page
- Verify route `transaksi.parkir.index` exists
- Check middleware authorization

## Database Schema Validation

Run these queries to verify your schema:

```sql
-- Check enum values for status_pembayaran
DESCRIBE tb_transaksi;

-- Verify foreign key constraints
SELECT * FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE TABLE_NAME = 'tb_transaksi';

-- Check data availability
SELECT COUNT(*) as kendaraan FROM tb_kendaraan;
SELECT COUNT(*) as tarif FROM tb_tarif;
SELECT COUNT(*) as area FROM tb_area_parkir;
```

## Testing the Fix

1. Navigate to: `http://localhost/parkirApp/transaksi/check-in/create`
2. Verify dropdown lists show data
3. Select: Kendaraan, Tarif, Area Parkir
4. Click "Catat Masuk"
5. Check:
   - Success message appears
   - Redirects to parkir active list
   - New transaction appears in list
   - Area capacity increased by 1

## Files Modified

1. **app/Http/Controllers/TransaksiController.php** - Fixed `checkIn()` method
2. **app/Console/Commands/DiagnoseCheckIn.php** - Created new diagnostic command
3. **app/Console/Commands/SeedCheckInData.php** - Created new seed command

## Next Steps if Issue Persists

1. Check Laravel log file for detailed errors
2. Run diagnostic command to verify all data
3. Test in browser developer tools (check network tab)
4. Verify user has proper authentication/authorization
5. Check if observer is causing issues (TransaksiObserver)
