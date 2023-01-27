package com.pcs.apptoko

import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import androidx.navigation.Navigation.findNavController
import androidx.navigation.findNavController
import androidx.navigation.ui.setupActionBarWithNavController
import androidx.navigation.ui.setupWithNavController
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.pcs.apptoko.R.layout.activity_main


class MainActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(activity_main)

     val bottomNavigationView = findViewById<BottomNavigationView>(R.id.bottomNavigationView)
     val navController = findNavController(R.id.nav_fragment)
     bottomNavigationView.setupWithNavController(navController)
    }


}