masalah stock negative terjadi karena ada banyak request yang terjadi secara hampir bersamaan yang mengupdate stok produk yang sama (race condition)
saat user A akan checkout dan akan mengupdate stok produk 1, user B juga melakukan hal yang sama, ini menyebabkan perhitungan dan validasi stock menjadi salah

solusi dari masalah ini adalah dengan melakukan lock, sehingga request tersebut dapat dihandle secara sequential, 
sehingga jika user A akan mengupdate stok, stok yang dihitung masih dalam keadaan benar karena stok tersebut dilock, tidak ada user lain yang bisa mengupdate sampai user A menyelesaikan transaksi
dapat dilakukan pessimistic lock atau optimistic lock
