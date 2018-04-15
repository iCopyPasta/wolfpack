package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.DialogFragment;
import android.content.Context;
import android.content.DialogInterface;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.support.design.widget.TabLayout;
import android.support.v7.app.AppCompatActivity;

import android.support.v4.view.ViewPager;
import android.os.Bundle;
import android.util.Log;
import android.widget.Toast;

import com.wolfpack.cmpsc488.a475layouts.R;
import com.wolfpack.cmpsc488.a475layouts.TabAdapter;

import com.wolfpack.cmpsc488.a475layouts.services.WolfpackClient;
import com.wolfpack.cmpsc488.a475layouts.services.data_retrieval.BasicWolfpackResponse;
import com.wolfpack.cmpsc488.a475layouts.services.pagination.PaginationAdapter;
import com.wolfpack.cmpsc488.a475layouts.services.pagination.ClasslistPaginationAdapter;

import okhttp3.ResponseBody;
import retrofit2.Call;

public class StudentPage extends AppCompatActivity implements
        PaginationAdapter.onClassSelectToEnrollListener,
        AlertEnrollDialog.AlertEnrollDialogListener

{

    private static final String TAG = "StudentPage";
    private static WolfpackClient wolfpackClient;
    private TabAdapter mTabAdapter;
    private ViewPager mViewPager;
    private boolean isRunning;

    public String studentId;
    public String studentEmail;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_student_page);

        //getting student information
        SharedPreferences sharedPref = this.getSharedPreferences(
                getString(R.string.preference_file_key), Context.MODE_PRIVATE);

        studentId = sharedPref.getString(getString(R.string.STUDENT_ID),"0");
        studentEmail = sharedPref.getString(getString(R.string.USER_EMAIL), "<<default>>");

        //set up the viewpager with the sections adapter
        mViewPager = (ViewPager) findViewById(R.id.studentPageViewPager);
        setupViewPager(mViewPager);

        TabLayout tabLayout = (TabLayout) findViewById(R.id.studentPageTabs);
        tabLayout.setupWithViewPager(mViewPager);

        Log.i(TAG, "finished onCreate in StudentPage");
    }


    private void setupViewPager(ViewPager viewPager){
        TabAdapter adapter = new TabAdapter(getSupportFragmentManager());
        adapter.addFragment(new StudentPageTab1Classlist(), getResources().getString(R.string.student_page_tab1_classlist));
        adapter.addFragment(new StudentPageTab2AddClass(), getResources().getString(R.string.student_page_tab2_addclass));
        adapter.addFragment(new StudentPageTab3Settings(), getResources().getString(R.string.student_page_tab3_settings));
        viewPager.setAdapter(adapter);
    }

    public static synchronized WolfpackClient getWolfpackClientInstance(){
        if(wolfpackClient == null){
            return WolfpackClient.retrofit.create(WolfpackClient.class);
        } else{
            return wolfpackClient;
        }

    }

    @Override
    public void onEnrollPositiveClick(String class_id) {
        if(!isRunning){
            isRunning = true;
            new AsyncEnrollBackgroundTask().execute(studentId,class_id);
        }
    }

    @Override
    public void onEnrollNegativeClick() {

    }

    class AsyncEnrollBackgroundTask extends AsyncTask<String, Void, Boolean> {

        @Override
        protected Boolean doInBackground(String... strings) {
            BasicWolfpackResponse answer;
            try{
                WolfpackClient client = WolfpackClient.retrofit.create(WolfpackClient.class);
                Call<BasicWolfpackResponse> callback = client.enrollForClass(
                        strings[0],
                        strings[1],
                        "enrollForClass"
                );
                answer = callback.execute().body();

            }
            catch (IllegalStateException e) {
                Log.e(TAG, e.getMessage());
                return false;

            } catch(java.net.ConnectException e){
                Log.e(TAG, e.getMessage());

                return false;
            } catch (Exception e){
                Log.e(TAG, e.getMessage());
                return false;
            }

            Log.i(TAG, "doInBackground: " + "answer is not null: " + Boolean.toString(answer != null));
            if(answer != null)
                Log.i(TAG, "ANSWER MESSAGE: " + answer.getMessage());


            return answer != null && answer.getStatus() > 0;
        }

        @Override
        protected void onPostExecute(Boolean status){
            if(status){
                isRunning = false;
                Toast.makeText(StudentPage.this, "enrolled!", Toast.LENGTH_SHORT).show();

            }
        }
    }


    /**
     * Interface implementation for
     *      PaginationAdapter.onClassSelectToEnrollListener
     * @param student_id
     * @param class_id
     */

    @Override
    public void onClassSelected(String student_id, String class_id, String title, String description) {
        Log.i(TAG, "onClassSelected: " + student_id + " " + class_id);

        AlertEnrollDialog dialog = new AlertEnrollDialog();
        Bundle info = new Bundle();
        info.putString("student_id", student_id);
        info.putString("class_id", class_id);
        info.putString("title", title);
        info.putString("description", description);

        dialog.setInfo(info);
        dialog.show(getFragmentManager(),"AlertEnrollDialog");
    }


}











