package com.pcs.apptoko

import android.content.Intent
import android.os.Bundle
import android.util.Log
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.TextView
import android.widget.Toast
import androidx.navigation.fragment.findNavController
import com.google.android.material.textfield.TextInputEditText
import com.pcs.apptoko.api.BaseRetrofit
import com.pcs.apptoko.response.login.LoginResponse
import com.pcs.apptoko.response.produk.Produk
import com.pcs.apptoko.response.produk.ProdukResponsePost
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response


class ProdukFormFragment : Fragment() {

    private val api by lazy { BaseRetrofit().endpoint }

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        // Inflate the layout for this fragment
        val view = inflater.inflate(R.layout.fragment_produk_form, container, false)

        val btnProsesProduk = view.findViewById(R.id.btnProsesProduk) as Button

        val txtFormNama = view.findViewById(R.id.txtFormNama) as TextView
        val txtFormHarga = view.findViewById(R.id.txtFormHarga) as TextView
        val txtFormStok = view.findViewById(R.id.txtFormStok) as TextView

        val status = arguments?.getString("status")
        val produk = arguments?.getParcelable<Produk>("produk")

        Log.d("produkForm",produk.toString())

        if(status=="edit"){
            txtFormNama.setText(produk?.nama.toString())
            txtFormHarga.setText(produk?.harga.toString())
            txtFormStok.setText(produk?.stok.toString())
        }

        btnProsesProduk.setOnClickListener{
            //Aksi untuk menyimpan
            val txtFormNama = view.findViewById(R.id.txtFormNama) as TextInputEditText
            val txtFormHarga = view.findViewById(R.id.txtFormHarga) as TextInputEditText
            val txtFormStok = view.findViewById(R.id.txtFormStok) as TextInputEditText

            val token = LoginActivity.sessionManager.getString("TOKEN")
            val adminId = LoginActivity.sessionManager.getString("ADMIN_ID")

            if(status=="edit"){
                api.putProduk(token.toString(),produk?.id.toString().toInt(),adminId.toString().toInt(),txtFormNama.text.toString(),txtFormHarga.text.toString().toInt(),txtFormStok.text.toString().toInt()).enqueue(object :
                    Callback<ProdukResponsePost> {
                    override fun onResponse(
                        call: Call<ProdukResponsePost>,
                        response: Response<ProdukResponsePost>
                    ) {
                        Log.d("ResponData",response.body()!!.data.toString())
                        Toast.makeText(activity?.applicationContext, "Data "+ response.body()!!.data.produk.nama.toString() +" di edit", Toast.LENGTH_SHORT).show()

                        findNavController().navigate(R.id.produkFragment)
                    }

                    override fun onFailure(call: Call<ProdukResponsePost>, t: Throwable) {
                        Log.e("Error",t.toString())
                    }

                })
            }else{
                api.postProduk(token.toString(),adminId.toString().toInt(),txtFormNama.text.toString(),txtFormHarga.text.toString().toInt(),txtFormStok.text.toString().toInt()).enqueue(object :
                    Callback<ProdukResponsePost> {
                    override fun onResponse(
                        call: Call<ProdukResponsePost>,
                        response: Response<ProdukResponsePost>
                    ) {
                        Log.d("Data",response.toString())
                        Toast.makeText(activity?.applicationContext, "Data di Input", Toast.LENGTH_SHORT).show()

                        findNavController().navigate(R.id.produkFragment)
                    }

                    override fun onFailure(call: Call<ProdukResponsePost>, t: Throwable) {
                        Log.e("Error",t.toString())
                    }

                })
            }



        }

        return view
    }

}