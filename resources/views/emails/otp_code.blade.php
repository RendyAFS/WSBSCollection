<!DOCTYPE html>
<html>
<head>
    <title>Kode OTP</title>
</head>
<body>
    <p>Kode OTP validasi registrasi akun Witel Suramadu Data Collection: <strong>{{ $otpCode }}</strong></p>
    <p>Detail Akun yang Terdaftar:</p>
    <ul>
        <li>Level: {{ e($userData['level']) }}</li>
        <li>Status: {{ e($userData['status']) }}</li>
        <li>Nama: {{ e($userData['name']) }}</li>
        <li>NIK: {{ e($userData['nik']) }}</li>
        <li>No. HP: {{ e($userData['no_hp']) }}</li>
        <li>Email: {{ e($userData['email']) }}</li>
        <li>Tanggal Registrasi: {{ e($userData['created_at']) }}</li>
    </ul>
    <p>Silahkan Melakukan validasi email, silahkan menunggu <strong>Aktivasi</strong> akun dari Super Admin</p>
</body>
</html>
