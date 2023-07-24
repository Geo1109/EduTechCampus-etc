package com.example.elevcampusapp;

import androidx.appcompat.app.AppCompatActivity;

import android.annotation.SuppressLint;
import android.app.DatePickerDialog;
import android.app.TimePickerDialog;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.TextView;
import android.widget.TimePicker;
import android.widget.Toast;

import com.google.android.gms.auth.api.signin.GoogleSignIn;
import com.google.android.gms.auth.api.signin.GoogleSignInAccount;
import com.google.android.gms.auth.api.signin.GoogleSignInClient;
import com.google.android.gms.auth.api.signin.GoogleSignInOptions;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.Task;

import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

public class ElevActivity extends AppCompatActivity {
    GoogleSignInOptions gso;
    GoogleSignInClient gsc;
    TextView name;
    TextView email;
    TextView orgunit;
    Button signOutBtn;
    Button btn;
    TextView textdata;
    Button butondate;
    TextView textora,cartela;
    Button butonora;
    Button biletvoie,oxy,water;
    Button descanat;

    //private static final String TARGET_APP_PACKAGE_NAME = "com.example.nfc_test";
    private static final String TARGET_APP_PACKAGE_NAME = "com.example.nfc_test";
    private static final String EXTRA_VARIABLE_NAME = "ID";


    @SuppressLint("MissingInflatedId")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_elev);

        name = findViewById(R.id.name);
        email = findViewById(R.id.email);
        signOutBtn = findViewById(R.id.signout);
        orgunit = findViewById(R.id.orgunit);
        btn = findViewById(R.id.scanare);
        biletvoie = findViewById(R.id.biletdevoie);
        oxy = findViewById(R.id.adeverinta);
        water = findViewById(R.id.meniucantina);
        //cartela =findViewById(R.id.cartelala);




       /* Bundle extras = getIntent().getExtras();
        if (extras != null) {
            String value;
            value = extras.getString("key1");
            //The key argument here must match that used in the other activity
            cartela.setText(value+"834628");
            System.out.println("1234"+value);
            Toast.makeText(getApplicationContext(),value,Toast.LENGTH_SHORT).show();
        }
        else{
            Toast.makeText(getApplicationContext(),"nimiccc",Toast.LENGTH_SHORT).show();
            cartela.setText("127536475");
        }*/





        biletvoie.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent i = new Intent(ElevActivity.this, BiletDeVoieActivity.class);
                i.putExtra("key1",name.getText().toString());
                i.putExtra("key2",email.getText().toString());
                i.putExtra("key3",orgunit.getText().toString());

                startActivity(i);
            }
        });

        oxy.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String targetAppPackageName = "com.example.testwifi3.MainActivity";
                Intent i = getPackageManager().getLaunchIntentForPackage(targetAppPackageName);

                startActivity(i);
            }
        });
        water.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String websiteurl = "https://www.wikipedia.org/";
                Intent i = new Intent(Intent.ACTION_VIEW,Uri.parse(websiteurl));

                startActivity(i);
            }
        });


        signOutBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                signOut();
            }
        });


        gso = new GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN).requestEmail().build();
        gsc = GoogleSignIn.getClient(this, gso);

        GoogleSignInAccount acct = GoogleSignIn.getLastSignedInAccount(this);
        if (acct != null) {
            String personName = acct.getDisplayName();
            String personEmail = acct.getEmail();
            String personOrgUnit = acct.getId();
            name.setText(personName);
            email.setText(personEmail);
            orgunit.setText(personOrgUnit);
        }

       /* btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String variableValue = orgunit.getText().toString();
                Intent intent = new Intent(Intent.ACTION_MAIN);
                intent.setClassName("com.example.nfc_test","com.example.nfc_test.MainActivity");
                intent.putExtra("key", variableValue);
                startActivity(intent);
                Toast.makeText(getApplicationContext(),"scanare",Toast.LENGTH_SHORT).show();
            }
        });*/


        btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent i = new Intent(ElevActivity.this, NFCActivity.class);
                Toast.makeText(getApplicationContext(),"scanare",Toast.LENGTH_SHORT).show();
                i.putExtra("key10",email.getText().toString());
                i.putExtra("key11",orgunit.getText().toString());
                startActivity(i);
            }
        });




    }






    void signOut() {
        gsc.signOut().addOnCompleteListener(new OnCompleteListener<Void>() {
            @Override
            public void onComplete(Task<Void> task) {
                finish();
                startActivity(new Intent(ElevActivity.this, LogareActivity.class));
            }
        });
    }



}

