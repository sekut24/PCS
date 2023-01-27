package com.pcs.apptoko.response.cart

import android.os.Parcelable
import kotlinx.parcelize.Parcelize

//Aksi untuk mengirim data dalam satu paket
@Parcelize
data class Cart(
    var id: Int,
    var harga: Int,
    var qty: Int
):Parcelable
