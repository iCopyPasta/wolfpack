package com.wolfpack.cmpsc488.a475layouts.services.pollingsession;

import android.app.job.JobParameters;
import android.app.job.JobService;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Build;
import android.os.PersistableBundle;
import android.support.annotation.RequiresApi;
import android.support.v4.content.LocalBroadcastManager;
import android.util.Log;

@RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
public class MyJobService extends JobService {
    private final String TAG = "MyJobService";

    public MyJobService() {
        Log.i(TAG, "MyJobService: " + "created in constructor");
    }

    @Override
    public boolean onStartJob(final JobParameters jobParameters) {

        Log.i(TAG, "onStartJob: " + "starting job on background thread");
        (new ServiceJobAsyncTask()).execute(jobParameters);

        //return true to indicate we are not finished, we are performing a task asynchronously
        return true;
    }

    @Override
    public boolean onStopJob(JobParameters jobParameters) {

        Log.i(TAG, "onStopJob: ");
        return false;
    }

    class ServiceJobAsyncTask extends AsyncTask<JobParameters, Void, String>{

        private final String TAG = "ServiceJobAT";
        private JobParameters jobParameters;

        @Override
        protected String doInBackground(JobParameters... params) {

            jobParameters = params[0];
            final PersistableBundle persistableBundle = jobParameters.getExtras();

            //TODO: perform actual network request based off of parameters

            Runnable r = new Runnable() {
                @Override
                public void run() {
                    try {
                        Thread.sleep(4000);
                    } catch (InterruptedException e) {
                        e.printStackTrace();
                    }
                    Log.i(TAG, "run: " + persistableBundle.getString("class"));

                    Log.i(TAG, "run: " + "finished our background task");
                }
            };

            Thread thread = new Thread(r);
            thread.start();

            return "some switch";
        }

        @Override
        protected void onPostExecute(final String params){

            //TODO: logic based on the response we get from the server
            switch (params){
                case "some switch":
                    Intent intent = new Intent("ServiceMessage");
                    intent.putExtra("key", "value");
                    intent.putExtra("secondKey", "secondValue");

                    LocalBroadcastManager.getInstance(
                            getApplicationContext())
                            .sendBroadcast(intent);

                    jobFinished(jobParameters, true);
                    break;

                case "other case":
                    break;
            }

        }
    }


}
