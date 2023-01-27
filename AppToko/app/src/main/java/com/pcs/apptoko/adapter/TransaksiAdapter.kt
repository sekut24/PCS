package com.pcs.apptoko.adapter


import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageButton
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import com.pcs.apptoko.CallbackInterface
import com.pcs.apptoko.R
import com.pcs.apptoko.response.cart.Cart
import com.pcs.apptoko.response.produk.Produk
import java.text.NumberFormat
import java.util.*
import kotlin.collections.ArrayList

class TransaksiAdapter(val listProduk: List<Produk>): RecyclerView.Adapter<TransaksiAdapter.ViewHolder>() {

    var callbackInterface: CallbackInterface? = null

    //Inisiasi untuk menampung total
    var total : Int = 0

    //Inisiasi untuk cart
    var cart: ArrayList<Cart> = arrayListOf<Cart>()

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): TransaksiAdapter.ViewHolder {
        val view = LayoutInflater.from(parent.context).inflate(R.layout.item_transaksi,parent,false)
        return TransaksiAdapter.ViewHolder(view)
    }

    override fun getItemCount(): Int {
        return listProduk.size
    }

    override fun onBindViewHolder(holder: TransaksiAdapter.ViewHolder, position: Int) {
        val produk = listProduk[position]
        holder.txtNamaProduk.text = produk.nama

        val localeID =  Locale("in", "ID")
        val numberFormat = NumberFormat.getCurrencyInstance(localeID)


        holder.txtHarga.text = numberFormat.format(produk.harga.toDouble()).toString()

        //Aksi untuk tombol plus
        holder.btnPlus.setOnClickListener{
            val old_value = holder.txtQty.text.toString().toInt()
            val new_value = old_value+1

            holder.txtQty.setText(new_value.toString())

            //Melakukan aksi penambahan jika plus
            total = total + produk.harga.toString().toInt()


            val index = cart.indexOfFirst { it.id == produk.id.toInt() }.toInt()

            if(index!=-1){
                cart.removeAt(index)
            }

            val itemCart = Cart(produk.id.toInt(),produk.harga.toInt(),new_value)
            cart.add(itemCart)

            callbackInterface?.passResultCallback(total.toString(),cart)


        }


        //Aksi untuk tombol minus
        holder.btnMinus.setOnClickListener{
            val old_value = holder.txtQty.text.toString().toInt()
            val new_value = old_value-1



            val index = cart.indexOfFirst { it.id == produk.id.toInt() }.toInt()

            if(index!=-1){
                cart.removeAt(index)
            }


            if (new_value>=0) {
                holder.txtQty.setText(new_value.toString())

                //Melakukan aksi pengurangan jika minus
                total = total - produk.harga.toString().toInt()

            }

            if(new_value>=1){
                val itemCart = Cart(produk.id.toInt(),produk.harga.toInt(),new_value)
                cart.add(itemCart)
            }


            //Aksi untuk mengirim data dan akan dipanggil di TransaksiFragment
            callbackInterface?.passResultCallback(total.toString(),cart)



        }
    }

    class ViewHolder(itemView : View) : RecyclerView.ViewHolder(itemView) {
        val txtNamaProduk = itemView.findViewById(R.id.txtNamaProduk) as TextView
        val txtHarga = itemView.findViewById(R.id.txtHarga) as TextView
        val txtQty = itemView.findViewById(R.id.txtQty) as TextView
        val btnPlus = itemView.findViewById(R.id.btnPlus) as ImageButton
        val btnMinus = itemView.findViewById(R.id.btnMinus) as ImageButton

    }

}