package com.pcs.apptoko.adapter

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import com.pcs.apptoko.R
import com.pcs.apptoko.response.transaksi.Transaksi
import java.text.NumberFormat
import java.util.*

class LaporanAdapter(val ListTransaksi : List<Transaksi>):RecyclerView.Adapter<LaporanAdapter.ViewHolder>() {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
        val view = LayoutInflater.from(parent.context).inflate(R.layout.item_laporan,parent,false)
        return LaporanAdapter.ViewHolder(view)
    }

    override fun getItemCount(): Int {
        return ListTransaksi.size
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        val transaksi = ListTransaksi[position]
        holder.txtTglTransaksi.text = transaksi.tanggal
        holder.txtNoNota.text = "#0000"+transaksi.id

        val localeID =  Locale("in", "ID")
        val numberFormat = NumberFormat.getCurrencyInstance(localeID)

        holder.txtItemTransaksi.text = numberFormat.format(transaksi.total.toDouble()).toString()
    }

    class ViewHolder(itemView : View) : RecyclerView.ViewHolder(itemView) {
        val txtTglTransaksi = itemView.findViewById(R.id.txtTglTransaksi) as TextView
        val txtNoNota = itemView.findViewById(R.id.txtNoNota) as TextView
        val txtItemTransaksi = itemView.findViewById(R.id.txtItemTransaksi) as TextView
    }
}