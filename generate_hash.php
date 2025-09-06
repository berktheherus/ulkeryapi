<?php

// Bu dosyayı yeni bir şifre hash'i oluşturmak için kullanın.
// Kullanımı: Tarayıcıda http://localhost/ulkeryapi/generate_hash adresini açın.
// Oluşturulan hash'i kopyalayıp login.php dosyasındaki $password_hash değişkenine yapıştırın.
// İŞİNİZ BİTTİKTEN SONRA BU DOSYAYI SİLİN!

$password_to_hash = 'test'; // Buraya yeni şifrenizi yazın

$hashed_password = password_hash($password_to_hash, PASSWORD_DEFAULT);

echo "Şifreniz: " . htmlspecialchars($password_to_hash) . "<br>";
echo "Oluşturulan Hash: " . htmlspecialchars($hashed_password);
?>