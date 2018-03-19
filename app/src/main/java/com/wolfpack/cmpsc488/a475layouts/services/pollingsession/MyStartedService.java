package com.wolfpack.cmpsc488.a475layouts.services.pollingsession;

import android.app.Service;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Binder;
import android.os.IBinder;
import android.support.v4.content.LocalBroadcastManager;
import android.util.Log;

import java.util.Random;

public class MyStartedService extends Service {

    private final String TAG = "MyStartedService";
    private boolean isRunning = false;

    private final Binder mBinder = new MyServiceBinder();

    public MyStartedService() {
        Log.i(TAG, "MyStartedService: ");
    }

    @Override
    public IBinder onBind(Intent intent) {
        Log.i(TAG, "onBind: ");
        return mBinder;
    }

    public class MyServiceBinder extends Binder {

        public MyStartedService getService(){
            Log.i(TAG, "getService: ");
            return MyStartedService.this;
        }
    }

    @Override
    public void onCreate(){
        isRunning = false;
        super.onCreate();
    }

    @Override
    public void onDestroy(){
        isRunning = false;
        super.onDestroy();
    }

    @Override
    public boolean onUnbind(Intent intent){
        Log.i(TAG, "onUnbind: MyStartedService");
        return super.onUnbind(intent);
    }

    // methods to use in order to invoke our background task

    public String testValue(){
        if(!isRunning)
            (new ServiceJobAsyncTask()).execute();

        return "async approach started!";
    }

    //experimental test of String... passing
    public void requestActiveSession(String... params){
        if(!isRunning)
            (new ServiceJobAsyncTask()).execute(params);
    }


    class ServiceJobAsyncTask extends AsyncTask<String, Void, String> {

        private final String TAG = "ServiceJobAT";



        @Override
        protected String doInBackground(String... params) {
            isRunning = true;

            for (String el:params) {
                Log.i(TAG, "doInBackground: " + el);
            }

            //TODO: perform actual network request based off of parameters

            try {
                Thread.sleep(5000);
                //simulate our network call

            }  catch (InterruptedException e) {
                e.printStackTrace();
            }

            String[] testValues = {"poll", "no poll","no poll"};
            Random random = new Random();
            int randomInteger = random.nextInt(testValues.length);
            Log.i(TAG, "doInBackground: " + randomInteger);

            return testValues[randomInteger];
        }

        @Override
        protected void onPostExecute(final String params){
            isRunning = false;

            //TODO: logic based on the response we get from the server
            switch (params){
                case "poll": {
                    Intent intent = new Intent("ServiceMessage");
                    intent.putExtra("key", "poll");

                    //send our response back
                    LocalBroadcastManager.getInstance(
                            getApplicationContext())
                            .sendBroadcast(intent);
                }
                break;

                case "no poll": {
                    Intent intent = new Intent("ServiceMessage");
                    intent.putExtra("key", "no poll");

                    //send our response back
                    LocalBroadcastManager.getInstance(
                            getApplicationContext())
                            .sendBroadcast(intent);
                }
                    break;
            }


        }
    }
}
