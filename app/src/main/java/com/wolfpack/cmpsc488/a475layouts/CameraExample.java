package com.wolfpack.cmpsc488.a475layouts;

import android.Manifest;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.provider.MediaStore;
import android.support.v4.app.ActivityCompat;
import android.support.v4.content.ContextCompat;
import android.support.v4.content.FileProvider;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.widget.ImageView;
import android.widget.Toast;

import com.wolfpack.cmpsc488.a475layouts.experiences.student.StudentPage;
import com.wolfpack.cmpsc488.a475layouts.services.WolfpackClient;
import com.wolfpack.cmpsc488.a475layouts.services.data_retrieval.BasicWolfpackResponse;

import java.io.File;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.Date;

import okhttp3.MediaType;
import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Response;


/**
 * Updated by peo5032 on 3/11/18.
 * This java object will now incorporate Retrofit to upload an image to a server
 */

    //the problem is that the call to INSERT CALL requires that the URI have content!
    //most tutorials have file:// as the tag, which will cause newer versions of Android to crash,
    //starting with Android 6 I believe
public class CameraExample extends AppCompatActivity {

    public static final String MAIN_ACTIVITY = "CameraExample";
    String mCurrentPhotoPath;
    File photoFile = null;
    Uri photoURI = null;
    private static final int REQUEST_IMAGE_CAPTURE = 1;
    private static final int MY_PERMISSIONS_REQUEST_WRITE_PUBLIC_DIRECTORY = 2;
    boolean isUploading = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_camera_example);
        Log.i(MAIN_ACTIVITY, "in process of creation");

    }

    /**
     * Get the necessary calls in place
     * This was used as a placeholder for other trial attempts to experiment calling another app
     * There is the "Choose Once" vs. "Always" method and a more general always choose the app type
     *
     * @param view
     */
    public void getCameraAction(View view){
        Log.i(MAIN_ACTIVITY, "button clicked successfully registered");

        Uri number = Uri.parse("tel:7175999005");
        Intent callIntent = new Intent(Intent.ACTION_DIAL, number);

        //EXAMPLE OF INTENT'S STARTING ANOTHER APP

        //VERSION 1 --> A "Just Once" or "Always" type of approach
        // Verify it resolves
                /*PackageManager packageManager = getPackageManager();
                List<ResolveInfo> activities = packageManager.queryIntentActivities(callIntent, 0);
                boolean isIntentSafe = activities.size() > 0;

                // Start an activity if it's safe
                if (isIntentSafe) {
                    startActivity(callIntent);
                }*/

        //VERSION 2 --> A dynamic array of choices that always appears
                /*Intent chooser = Intent.createChooser(callIntent, "Choose an app to share with");

                if(callIntent.resolveActivity(getPackageManager()) != null){
                    startActivity(chooser);
                }*/


        //VERSION 3 --> Camera Application to Show Picture and Usage Back in Application

        //can we write to the public directory?
        requestPermissions();
    }


    /**
     * Callback code that is used by another application. in this case, our camera application
     * will use this method then
     * @param requestCode
     * @param resultCode
     * @param data
     */

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        Log.i(MAIN_ACTIVITY, "callback received!");
        if (requestCode == REQUEST_IMAGE_CAPTURE && resultCode == RESULT_OK) {
            Log.i(MAIN_ACTIVITY, "Capture callback!");

            //potentially show image for how-to?
            try{
                ImageView iView = (ImageView) findViewById(R.id.imageView);
                iView.setImageURI(photoURI);
                Log.i(MAIN_ACTIVITY, "onActivityResult:" );

                //Pokemon Exception Handling
            }catch(Exception e){
                Log.e(MAIN_ACTIVITY, e.getLocalizedMessage());

            }
        }
    }

    /**
     * Create a file to overwrite, file is NOT passed back in Intent data's
     * Camera intent needs to pass URI that represents the file
     * This is set by Android's "Strict Mode"
     * @return File location which Camera will overwrite with informaiton
     * @throws IOException
     */
    private File createImageFile() throws IOException {

        // Create an image file name
        String timeStamp = new SimpleDateFormat("yyyyMMdd_HHmmss").format(new Date());
        String imageFileName = "JPEG_" + timeStamp + "_";
        File storageDir = getFilesDir();
        Log.i(MAIN_ACTIVITY, "storageDir is: " + storageDir);

        File imageDir = new File(storageDir, "AL");
        imageDir.mkdir();

        File image = new File(imageDir, imageFileName + ".jpg");

        // Save a file: path for use with ACTION_VIEW intents
        mCurrentPhotoPath = image.getAbsolutePath();

        return image;
    }

    /**
     * Should always seek permissions before running a "dangerous" permission-level process
     * Users (while not likely), have the ability to revoke permissions.
     * We should not assume we are guaranteed permissions forever
     */
    protected void requestPermissions(){
        Log.i(MAIN_ACTIVITY, "entering requestPermissions:");

        // Here, thisActivity is the current activity
        if (ContextCompat.checkSelfPermission(this,
                Manifest.permission.WRITE_EXTERNAL_STORAGE)
                != PackageManager.PERMISSION_GRANTED) {

            // Should we show an explanation?
            if (ActivityCompat.shouldShowRequestPermissionRationale(this,
                    Manifest.permission.WRITE_EXTERNAL_STORAGE)) {

                // Show an explanation to the user *asynchronously* -- don't block
                // this thread waiting for the user's response! After the user
                // sees the explanation, try again to request the permission.

            } else {

                // No explanation needed, we can request the permission.

                ActivityCompat.requestPermissions(this,
                        new String[]{Manifest.permission.WRITE_EXTERNAL_STORAGE},
                        MY_PERMISSIONS_REQUEST_WRITE_PUBLIC_DIRECTORY);
            }
        }

        else{
            dispatchTakePictureIntent();
        }
    }

    /**
     * Go through with the process of taking a picture
     * Will startActivityForResults to a registered camera application
     */

    protected void dispatchTakePictureIntent() {

        Log.i(MAIN_ACTIVITY, "entering picture taking");
        Intent takePictureIntent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
        // Ensure that there's a camera activity to handle the intent
        if (takePictureIntent.resolveActivity(getPackageManager()) != null) {
            // Create the File where the photo should go
            try {
                this.photoFile = createImageFile();
                Log.i(MAIN_ACTIVITY, "image name is: " + this.photoFile.getName());
                Log.i(MAIN_ACTIVITY, "location is: " + this.photoFile.getAbsolutePath());

            } catch (IOException ex) {
                // Error occurred while creating the File
                Log.e(MAIN_ACTIVITY, ex.getMessage());
            }

            Log.i(MAIN_ACTIVITY, "attempting to create URI and start Camera Activity");
            // Continue only if the File was successfully created
            if (photoFile != null) {

                //For camera events, data is written to the file and not returned in the data Intent
                //Uri photoURI = Uri.fromFile(this.photoFile); //should throw exception in new versions of Android
                this.photoURI = FileProvider.getUriForFile(
                        this,
                        "com.wolfpack.cmpsc488.a475layouts.FileProvider",
                        this.photoFile
                );

                takePictureIntent.putExtra(MediaStore.EXTRA_OUTPUT, photoURI);

                Log.i(MAIN_ACTIVITY, "onto the camera!");
                startActivityForResult(takePictureIntent, REQUEST_IMAGE_CAPTURE);
            }
        }
    }

    /**
     * callback: this is the callback from Android framkework and user pressing "allow"/"deny"
     * if this is the first time, JUST FOR THIS TIME, we can run the dispathTakePictureIntent
     */
    @Override
    public void onRequestPermissionsResult(int requestCode,
                                           String permissions[], int[] grantResults) {
        Log.i(MAIN_ACTIVITY, "user or system responded to permission!");

        switch (requestCode) {
            case MY_PERMISSIONS_REQUEST_WRITE_PUBLIC_DIRECTORY: {
                // If request is cancelled, the result arrays are empty.
                if (grantResults.length > 0
                        && grantResults[0] == PackageManager.PERMISSION_GRANTED) {

                    // permission was granted, yay! Do the camera thing!
                    // will either be auto-granted if the user already confirmed OR
                    // reassigned persmission by the user themselves
                    dispatchTakePictureIntent();

                } else {
                    // permission denied, boo! Disable the
                    // functionality that depends on this permission.
                    Log.i(MAIN_ACTIVITY, "Permission Denied!");
                }

            }

            // other 'case' lines to check for other
            // permissions this app might request.
        }
    }

    public void onCameraUpload(View view){
        //Toast.makeText(this, "to implement, yet", Toast.LENGTH_SHORT).show();


        if(!isUploading){
            isUploading = true;

            (new ResultBackgroundTask()).execute();
        }

    }

    class ResultBackgroundTask extends AsyncTask<String, Void, BasicWolfpackResponse> {

        private static final String TAG = "ResultBackgroundTask";

        Response<BasicWolfpackResponse> response;
        WolfpackClient client;

        @Override
        protected BasicWolfpackResponse doInBackground(String... params) {
            Log.i(TAG, "running new doInBackground task");

            try {
                Log.i(TAG, "About to try network request out");

                client = StudentPage.getWolfpackClientInstance();

                Log.i(TAG, "setting call with parameters");

                RequestBody fileBody = RequestBody.create(MediaType.parse(
                        getContentResolver().getType(photoURI)), photoFile);

                //https://stackoverflow.com/questions/36491096/retrofit-multipart-request-required-multipartfile-parameter-file-is-not-pre/36514662
                MultipartBody body = new MultipartBody.Builder()
                        .addFormDataPart("file-type", "profile")
                        .addFormDataPart("inputUserPicture", photoFile.getName(), fileBody)
                        .addFormDataPart("inputMethodName", "uploadSinglePic" )
                        .build();
                Call<BasicWolfpackResponse> call =
                        client.uploadSinglePic(("multipart/form-data; boundary=" + body.boundary()) ,
                        "wolfpack.cs.hbg.psu.edu", body);
                Log.i(TAG, "waiting on potential values");
                response = call.execute();
                Log.i(TAG, "execution finished, returning body");

                return response.body();

                //TODO: ADD SECURE TRY-CATCH BLOCKS FOR VARIOUS POSSIBILITIES!
            } catch(java.net.ConnectException e){
                Log.e(TAG, e.getMessage());
                return null;
            }
            catch (IllegalStateException e){
                Log.e(TAG, e.getMessage());
                return null;

            } catch(NullPointerException e){
                Log.e(TAG, e.getMessage());
                return null;
            }
            catch (Exception e){
                Log.e(TAG, e.getClass().toString() + " "+  e.getMessage());
                return null;
            }
        }

        @Override
        protected void onPostExecute(final BasicWolfpackResponse result) {

            //we successfully retrieved a valid value back from server
            if(result != null){
                Log.i(TAG, "result was not null");
                Log.i(TAG, "we made it all the way to onPostExecute");
                Log.i(TAG, result.getMessage());
                Log.i(TAG, String.valueOf(result.getStatus()));
                isUploading = false;

                if(result.getStatus() == 1){
                    Toast.makeText(CameraExample.this, "Upload done", Toast.LENGTH_SHORT).show();

                }
            }
            else{

                Toast.makeText(CameraExample.this, "An error occurred", Toast.LENGTH_SHORT).show();
            }

        }

        @Override
        protected void onCancelled() {
            //TODO: show some error in the screen
            Toast.makeText(CameraExample.this, "Cancelled", Toast.LENGTH_SHORT).show();
        }
    }
}
