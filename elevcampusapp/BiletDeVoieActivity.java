package com.example.elevcampusapp;

import androidx.appcompat.app.AppCompatActivity;

import android.app.DatePickerDialog;
import android.app.TimePickerDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.TimePicker;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
//import com.google.androidgamesdk.gametextinput.Listener;


import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.security.SecureRandom;
import java.security.cert.X509Certificate;
import java.util.HashMap;
import java.util.Map;

import javax.net.ssl.HostnameVerifier;
import javax.net.ssl.SSLContext;
import javax.net.ssl.SSLSession;
import javax.net.ssl.TrustManager;
import javax.net.ssl.X509TrustManager;
import java.security.cert.CertificateException;
import java.security.cert.X509Certificate;
import java.security.NoSuchAlgorithmException;
import java.security.KeyManagementException;

import com.android.volley.toolbox.HurlStack;

import java.io.IOException;
import java.net.HttpURLConnection;
import java.net.URL;

import javax.net.ssl.HttpsURLConnection;
import javax.net.ssl.SSLSocketFactory;

public class BiletDeVoieActivity extends AppCompatActivity {
    TextView textdata, biletdevoie;
    Button butondate;
    TextView textora;
    Button butonora;
    TextView textdatasosire;
    Button butondatesosire;
    TextView textorasosire;
    Button butonorasosire, bback, SendButton;

    private EditText motivul, observat;
    //private static final String BASE_URL = "https://campus.lmvineu.ro/google_login/bilet_android.php?opt=send";
   // private static final String urlsend = "https://campus.lmvineu.ro/google_login/bilet_android.php?opt=send";
   private static final String urlsend = "https://192.168.1.138/oxygenie/edutech/bilet2.php?opt=command";
    //private static final String urlsend = "https://192.168.0.102/oxygenie/edutech/bilet2.php?opt=command";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_bilet_de_voie);

        NukeSSLCerts.nuke();

        biletdevoie = findViewById(R.id.biletdevoie);

        butondate = findViewById(R.id.butondata);
        textdata = findViewById(R.id.datatext);
        butondatesosire = findViewById(R.id.butondatasosire);
        textdatasosire = findViewById(R.id.datatextsosire);
        butonora = findViewById(R.id.butonora);
        textora = findViewById(R.id.oratext);
        butonorasosire = findViewById(R.id.butonorasosire);
        textorasosire = findViewById(R.id.oratextsosire);
        bback = findViewById(R.id.butondeback);
        SendButton = findViewById(R.id.butontrimitere);
        motivul = findViewById(R.id.motivul_plecarii);
        observat = findViewById(R.id.observatii);

        Intent i = getIntent();
        String numele = i.getStringExtra("key1");
        String emailul = i.getStringExtra("key2");
        String orgunitul = i.getStringExtra("key3");




        bback.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startActivity(new Intent(BiletDeVoieActivity.this, ElevActivity.class));
                System.out.println("1234 carnatttttt ");
            }
        });

        SendButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String md5id, nume, email, orgunit, motiv, dataplec, oraplec, datasos, orasos, obs;

                SharedPreferences sh = getSharedPreferences("MySharedPref", MODE_PRIVATE);
                nume = String.valueOf(numele);
                email = String.valueOf(emailul);
                md5id = md5(orgunitul);
                orgunit = String.valueOf(orgunitul);
                motiv = String.valueOf(motivul.getText());
                dataplec = String.valueOf(textdata.getText());
                oraplec = String.valueOf(textora.getText());
                datasos = String.valueOf(textdatasosire.getText());
                orasos = String.valueOf(textorasosire.getText());
                obs = String.valueOf(observat.getText());

                //Tv_status.setText(R.string.sendButton);
                //uniqueID=get_id();
                //auth_key=md5(uniqueID+"TaxiIneu");
                //sendOrders(auth_key, uniqueID, nume, tel, lat, lon, adresa, link, obs);
                sendOrders(nume, email, md5id, orgunit, motiv, dataplec, oraplec, datasos, orasos, obs);
                //Toast.makeText(MainActivity.this,R.string.sendButton, Toast.LENGTH_SHORT).show();
            }
        });


        butondate.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                openTimeDialog();
            }
        });
        butonora.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                openClockDialog();
            }
        });
        butondatesosire.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                openTimeDialogsosire();
            }
        });
        butonorasosire.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                openClockDialogsosire();
            }
        });


    }//on create

    private void openTimeDialog() {
        DatePickerDialog dialog = new DatePickerDialog(this, new DatePickerDialog.OnDateSetListener() {
            @Override
            public void onDateSet(DatePicker datePicker, int year, int month, int day) {
                textdata.setText(String.valueOf(year) + "-" + String.valueOf(month + 1) + "-" + String.valueOf(day));
                Log.d("DATA DATEPICKER", datePicker.getClass().getName());
            }
        }, 2023, 0, 12);
        dialog.show();

    }

    private void openClockDialog() {
        TimePickerDialog dialogtime = new TimePickerDialog(this, new TimePickerDialog.OnTimeSetListener() {
            @Override
            public void onTimeSet(TimePicker timePicker, int hours, int minutes) {
                textora.setText(String.valueOf(hours) + ":" + String.valueOf(minutes) + ":" + "00");
            }
        }, 15, 00, true);
        dialogtime.show();


    }

    private void openTimeDialogsosire() {
        DatePickerDialog dialog = new DatePickerDialog(this, new DatePickerDialog.OnDateSetListener() {
            @Override
            public void onDateSet(DatePicker datePicker, int year, int month, int day) {
                textdatasosire.setText(String.valueOf(year) + "-" + String.valueOf(month + 1) + "-" + String.valueOf(day));
            }
        }, 2023, 0, 12);
        dialog.show();

    }

    private void openClockDialogsosire() {
        TimePickerDialog dialogtime = new TimePickerDialog(this, new TimePickerDialog.OnTimeSetListener() {
            @Override
            public void onTimeSet(TimePicker timePicker, int hours, int minutes) {
                textorasosire.setText(String.valueOf(hours) + ":" + String.valueOf(minutes) + ":" + "00");
            }
        }, 15, 00, true);
        dialogtime.show();


    }


    private void sendOrders(String nume, String email, String md5id, String orgunit, String motiv, String dataplec, String oraplec, String datasos, String orasos, String obs) {
        System.out.println("1234 " + nume + " " + email + " " + orgunit + " " + motiv + " " + dataplec + " " + oraplec + " " + datasos + " " + orasos + " " + obs);
        //s = e1.getText().toString();
        StringRequest stringRequest = new StringRequest(Request.Method.POST, urlsend,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Toast.makeText(BiletDeVoieActivity.this, response, Toast.LENGTH_LONG).show();
                        //System.out.println("1234 " + response);
                         biletdevoie.setText(response.toString());
                        // biletdevoie.setText(error.toString());

                        // biletdevoie.setText(response);


                    }
                },
                new Response.ErrorListener() {

                    @Override
                    public void onErrorResponse(VolleyError error) {
                        //System.out.println("1234 " + error.toString());
                        Toast.makeText(BiletDeVoieActivity.this, error.toString(), Toast.LENGTH_LONG).show();
                         //biletdevoie.setText(error.toString());

                    }
                }) {
            @Override
            protected Map<String, String> getParams() {

                Map<String, String> params = new HashMap<String, String>();
                //params.put("opt","send");
                params.put("full_name", nume);
                params.put("email", email);
                params.put("secret", md5id);
                params.put("login_id", orgunit);
                params.put("fmotiv", motiv);
                params.put("data1", dataplec);
                params.put("ora1", oraplec);
                params.put("data2", datasos);
                params.put("ora2", orasos);
                params.put("fobs", obs);
                params.putAll(params);
               // Toast.makeText(BiletDeVoieActivity.this, params.toString(), Toast.LENGTH_LONG).show();
                return params;


            }
        };
        RequestQueue requestQueue = Volley.newRequestQueue(BiletDeVoieActivity.this);
        requestQueue.add(stringRequest);

    }

    public static String md5(final String s) {
        final String MD5 = "MD5";
        try {
            // Create MD5 Hash
            MessageDigest digest = java.security.MessageDigest
                    .getInstance(MD5);
            digest.update(s.getBytes());
            byte messageDigest[] = digest.digest();

            // Create Hex String
            StringBuilder hexString = new StringBuilder();
            for (byte aMessageDigest : messageDigest) {
                String h = Integer.toHexString(0xFF & aMessageDigest);
                while (h.length() < 2)
                    h = "0" + h;
                hexString.append(h);
            }
            return hexString.toString();

        } catch (NoSuchAlgorithmException e) {
            e.printStackTrace();
        }
        return "";
    }

    public static class NukeSSLCerts {
        public static void nuke() {
            try {
                TrustManager[] trustAllCerts = new TrustManager[]{
                        new X509TrustManager() {
                            public X509Certificate[] getAcceptedIssuers() {
                                X509Certificate[] myTrustedAnchors = new X509Certificate[0];
                                return myTrustedAnchors;
                            }

                            @Override
                            public void checkClientTrusted(X509Certificate[] certs, String authType) {
                            }

                            @Override
                            public void checkServerTrusted(X509Certificate[] certs, String authType) {
                            }
                        }
                };

                SSLContext sc = SSLContext.getInstance("SSL");
                sc.init(null, trustAllCerts, new SecureRandom());
                HttpsURLConnection.setDefaultSSLSocketFactory(sc.getSocketFactory());
                HttpsURLConnection.setDefaultHostnameVerifier(new HostnameVerifier() {
                    @Override
                    public boolean verify(String arg0, SSLSession arg1) {
                        return true;
                    }
                });
            } catch (Exception e) {
            }
        }
    }//NukeSSLCerts





}