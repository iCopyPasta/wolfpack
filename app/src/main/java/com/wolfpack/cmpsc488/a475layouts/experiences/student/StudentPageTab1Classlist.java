package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.support.v4.app.Fragment;
import android.os.Bundle;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsListView;
import android.widget.AdapterView;
import android.widget.BaseAdapter;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.wolfpack.cmpsc488.a475layouts.R;
import com.wolfpack.cmpsc488.a475layouts.services.WolfpackClient;

import java.util.ArrayList;

import com.wolfpack.cmpsc488.a475layouts.services.pagination.ClasslistPaginationAdapter;
import com.wolfpack.cmpsc488.a475layouts.services.pagination.ILoadmore;
import com.wolfpack.cmpsc488.a475layouts.services.pagination.models.ClassListResult;
import com.wolfpack.cmpsc488.a475layouts.services.pagination.models.ClassResult;

import retrofit2.Call;
import retrofit2.Response;


public class StudentPageTab1Classlist extends Fragment {


    private static final String TAG = "SPTab1Classlist";
    private static final int FINISHED_STUDENT_PAGE = 0;

    private ArrayList<ClassResult> classlist = null;

    private RecyclerView mClasslistRecycler = null;
    public ClasslistPaginationAdapter adapter = null;

    private boolean isLoading = false;

    private String studentId;
    private String studentEmail;


    @SuppressLint("InflateParams")
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_student_page_tab1_classlist, container, false);

        Log.i(TAG, "onCreateView");

        return rootView;
    }




    @Override
    public void onActivityCreated(Bundle savedInstanceState){
        super.onActivityCreated(savedInstanceState);

        Log.i(TAG, "onActivityCreated");

    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data){
        //deleted the class
        if (requestCode == FINISHED_STUDENT_PAGE && resultCode == Activity.RESULT_OK){
            //TODO: modify classlist
        }
    }

    public void refreshClasses(){
        try{
            Log.d(TAG, "getting student information");
            studentId = ((StudentPage) getActivity()).studentId;
            studentEmail = ((StudentPage) getActivity()).studentEmail;

            classlist = new ArrayList<>();

            Log.d(TAG, "getting recycler view");
            mClasslistRecycler = getActivity().findViewById(R.id.classlistRecyclerView);
            mClasslistRecycler.setLayoutManager(new LinearLayoutManager(getActivity()));

            Log.d(TAG, "setting up and binding adapter");
            adapter = new ClasslistPaginationAdapter(mClasslistRecycler, classlist);
            mClasslistRecycler.setAdapter(adapter);
            mClasslistRecycler.setNestedScrollingEnabled(true);

            Log.d(TAG, "setting listeners");
            Log.d(TAG, "setLoadmore interfaces");
            adapter.setLoadmore(new ILoadmore() {
                @Override
                public void onLoadMore() {
                    if(isLoading){
                        Log.i("adapter:onLoadMore", "we avoided multiple requests!");
                    } else{
                        isLoading = true;
                        new ClassesResultBackgroundTask().execute(studentId);
                    }
                }
            });

            Log.d(TAG, "setOnClassClickedListener interface");
            adapter.setOnClassClickedListener(new ClasslistPaginationAdapter.OnClassClickedListener() {
                @Override
                public void onClassClicked(String classId, String classTitle, String classDesc, String classOffering, String classLocation, String teacherName) {

                    Log.d("onClassClicked", "going to " + classTitle + " page");

                    Intent intent = new Intent(getContext(), StudentClassPage.class);

                    intent.putExtra(getContext().getString(R.string.KEY_STUDENT_ID), studentId);
                    intent.putExtra(getContext().getString(R.string.KEY_CLASS_ID), classId);
                    intent.putExtra(getContext().getString(R.string.KEY_CLASS_TITLE), classTitle);
                    intent.putExtra(getContext().getString(R.string.KEY_CLASS_DESCRIPTION), classDesc);
                    intent.putExtra(getContext().getString(R.string.KEY_CLASS_OFFERING), classOffering);
                    intent.putExtra(getContext().getString(R.string.KEY_CLASS_LOCATION), classLocation);
                    intent.putExtra(getContext().getString(R.string.KEY_CLASS_TEACHER_NAME), teacherName);

                    startActivityForResult(intent, FINISHED_STUDENT_PAGE);
                }
            });

            mClasslistRecycler.setVisibility(View.VISIBLE);


            //load first batch
            Log.d(TAG, "loading first batch");
            isLoading = true;
            new ClassesResultBackgroundTask().execute(studentId);


        }
        catch(NullPointerException e){
            Log.e(TAG, e.getClass().toString() + e.getMessage());
        }
    }

    @Override
    public void onResume(){
        super.onResume();
        refreshClasses();

    }



    @SuppressLint("StaticFieldLeak")
    class ClassesResultBackgroundTask extends AsyncTask<String, Void, ClassListResult<ClassResult>>{

        private static final String TAG = "ClassesResultTask";

        Response<ClassListResult<ClassResult>> response;
        WolfpackClient client;

        @Override
        protected ClassListResult<ClassResult> doInBackground(String... params) {

            try{
                Log.i(TAG, "starting task");
                client = StudentPage.getWolfpackClientInstance();
                Log.i(TAG, "configuring params");

                Log.i(TAG, "currentPage = "+ adapter.getLastPageNumber() +
                        "\nrowsPerPage = " + adapter.getRowsPerPage() +
                        "\nstudent_id = " + params[0]);

                //Thread.sleep(1500);

                Call<ClassListResult<ClassResult>> call = client.findEnrolledClasses(
                        adapter.getLastPageNumber(),
                        adapter.getRowsPerPage(),
                        params[0],
                        "findEnrolledClasses");

                Log.i(TAG, "waiting for results");
                response = call.execute();
                Log.i(TAG, "received results");

                return response.body();

            }
            catch(java.net.ConnectException e){
                Log.e(TAG, e.getMessage());
                return null;
            }
            catch (IllegalStateException e){
                Log.e(TAG, e.getMessage());
                return null;
            }
            catch (ClassCastException e){
                Log.e(TAG, e.getClass().toString() + e.getMessage());
                return null;
            }
            catch (Exception e){
                Log.e(TAG, e.getClass().toString() + e.getMessage());
                return null;
            }

        }

        @Override
        protected void onPostExecute(final ClassListResult<ClassResult> result){
            if (result != null) {
                Log.i(TAG, result.toString());

                mClasslistRecycler.stopScroll();

                int length = adapter.getItemCount();

                adapter.setServerTotal(result.getTotalPages());
                classlist.addAll(result.getResults());
                adapter.notifyDataSetChanged();

                mClasslistRecycler.smoothScrollToPosition(length);

                isLoading = false;
                adapter.setLoaded();

                Toast.makeText(getActivity(), "Loaded more", Toast.LENGTH_SHORT).show();
            }
            else{
                Toast.makeText(getActivity(), "An error occurred", Toast.LENGTH_SHORT).show();
            }
        }


        @Override
        protected void onCancelled() {
            //TODO: show some error in the screen
            Toast.makeText(getActivity(), "Cancelled", Toast.LENGTH_SHORT).show();
        }


    }












}