package com.pcs.apptoko.response.transaksi

data class TransaksiResponse(
    val `data`: Data,
    val massage: String,
    val success: Boolean
)