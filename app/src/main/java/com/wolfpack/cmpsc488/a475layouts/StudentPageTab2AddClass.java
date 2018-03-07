package com.wolfpack.cmpsc488.a475layouts;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.support.v4.app.Fragment;
import android.os.Bundle;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.RadioGroup;
import android.widget.Toast;

import java.util.ArrayList;
import java.util.SimpleTimeZone;

import authentication_services.LoginDetails;
import authentication_services.LoginPage;
import authentication_services.WolfpackClient;
import pagination.PaginationAdapter;
import pagination.models.SearchResultSection;
import retrofit2.Call;
import retrofit2.Response;

public class StudentPageTab2AddClass extends Fragment {

    ArrayList<SearchResultSection> items = new ArrayList<>();
    PaginationAdapter adapter;
    EditText classIdSearchEditText;
    WolfpackClient apiCaller;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_student_page_tab2_addclass, container, false);

        return rootView;
    }

    @Override
    public void onActivityCreated(Bundle savedInstanceState){
        super.onActivityCreated(savedInstanceState);
        classIdSearchEditText = getActivity().findViewById(R.id.classIdSearchEditText);

        // Grab our recycler view and set its adapter;
        RecyclerView recyclerView = (RecyclerView) getActivity().findViewById(R.id.sectionResults);
        recyclerView.setLayoutManager(new LinearLayoutManager(getActivity()));
        adapter = new PaginationAdapter(recyclerView, getActivity(), items);
        recyclerView.setAdapter(adapter);


        // Set our listener to search based on the correct specification
        classIdSearchEditText.setOnKeyListener(
                new View.OnKeyListener() {
                    @Override
                    public boolean onKey(View view, int keyCode, KeyEvent keyEvent) {

                        // check if "enter" has been used
                        if(keyCode == KeyEvent.KEYCODE_ENTER &&
                                keyEvent.getAction() == KeyEvent.ACTION_UP){

                            // check which radio group button is used for our execution to
                            // our background task

                            switch (((RadioGroup) getActivity()
                                    .findViewById(R.id.classRadioGroup)).getCheckedRadioButtonId()
                                    ){
                                case R.id.addClassRadioButton:
                                    //TODO: run background task
                                    Toast.makeText(getActivity(), "implement, yet", Toast.LENGTH_SHORT).show();
                                    classIdSearchEditText.setEnabled(false);

                                    break;

                                case R.id.pendingInvitesRadioButton:
                                    //TODO: disable input and run background task
                                    break;

                            }
                        }
                        return false;
                    }
                }
        );

    }

    class ResultBackgroundTask extends AsyncTask<String, Void, Boolean> {

        private static final String TAG = "ResultBackgroundTask";

        LoginDetails loginDetails;

        Response<SearchResultSection> response;

        @Override
        protected Boolean doInBackground(String... params) {
            // TODO: attempt authentication against a network service.

            try {
                Log.i(TAG, "About to try network request out");
                // TODO: attempt authentication against a network service.


                Log.i(TAG, "setting call with parameters");

                Log.i(TAG, "waiting on potential values");

                //TODO: ADD SECURE TRY-CATCH BLOCKS FOR VARIOUS POSSIBILITIES!

                //TODO: return an appropriate boolean value
                return false;
            } catch (Exception e){
                Log.e(TAG, e.getMessage());
                return false;
            }

        }

        @Override
        protected void onPostExecute(final Boolean success) {
            //TODO: get results and update the values in our adapter

        }

        @Override
        protected void onCancelled() {
            //TODO: show some error in the screen
        }
    }



}
