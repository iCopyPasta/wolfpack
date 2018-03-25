package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.os.Handler;
import android.os.Message;
import android.support.v4.app.Fragment;
import android.os.Bundle;
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

import pagination.ILoadmore;
import pagination.models.ClassListResult;
import pagination.models.ClassResult;
import retrofit2.Call;
import retrofit2.Response;


public class StudentPageTab1Classlist extends Fragment {


    private static final String TAG = "SPTab1Classlist";

    private ListView mListViewClasses;
    private View footView;
    private MyAdapter adapter;

    private static String[] classlistTemp = {"CMPSC 460", "CMPSC 462", "CMPSC 463", "CMPSC 469","CMPSC 472", "CMPSC 488", "COMP 505", "COMP 511", "COMP 512", "COMP 519"};
    private static String[] classdesclistTemp = {"Principles of Programming Languages", "Data Structrues", "Design and Analysis of Algorithms", "Formal Languages with Applications", "Operating System Concepts", "Computer Science Project", "Theory of Computation", "Design and Anaylsis of Algorithms", "Advance Operating Systems", "Advanced Topics in Database Management Systems"};
    private static String[] classteacherlistTemp = {"Sukmoon Chang", "Jeremy Blum", "Jeremy Blum", "Sukmoon Chang", "Linda Null", "Hyuntae Na", "Thang Bui","Thang Bui", "Linda Null", "Linda Null"};


    private boolean isLoading = false;
    private int visibleThreshold = 5;
    private int lastVisibleItem, totalItemCount = 0;
    private boolean isInitialState = true;

    private int currentPage = 0;

    private ArrayList<ClassResult> classlist;

    private String email;
    private int student_id;


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

        LayoutInflater li = (LayoutInflater) getContext().getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        try {
            footView = li.inflate(R.layout.listview_foot_view, null);
            Log.i(TAG, "footView = "+footView);

            //populate list view
            // TODO: Use database to find classes that the student is enrolled
            //       Currently it is displaying a hard coded list for demonstrating purposes

            classlist = new ArrayList<>();

            mListViewClasses = (ListView) getActivity().findViewById(R.id.classListView);
            mListViewClasses.setVisibility(View.GONE);

            Log.i(TAG, "starting: mListViewClasses.size() = "+mListViewClasses.getCount());

            adapter = new MyAdapter(getContext());

            mListViewClasses.setAdapter(adapter);

            mListViewClasses.setOnItemClickListener(new AdapterView.OnItemClickListener() {
                @Override
                public void onItemClick(AdapterView<?> adapterView, View view, int i, long id) {
                    Intent intent = new Intent(getActivity(), StudentClassPage.class);
                    intent.putExtra("className", classlistTemp[i]);
                    Log.i(TAG, "hello from onItemClick");

                    startActivity(intent);
                }
            });


            mListViewClasses.setOnScrollListener(new AbsListView.OnScrollListener() {

                @Override
                public void onScrollStateChanged(AbsListView view, int scrollState) {

                }

                @Override
                public void onScroll(AbsListView view, int firstVisibleItem, int visibleItemCount, int totalItemCount) {
                    Log.i(TAG, "in mListViewClasses onScroll");
                    Log.i(TAG, "getLastVisiblePosition() = " + view.getLastVisiblePosition() +
                            "\ntotalItemCount == " + (totalItemCount - 1) +
                            "\nisLoading == " + isLoading);

                    //if at the bottom then load more results
                    if ((view.getLastVisiblePosition() == totalItemCount - 1 && mListViewClasses.getCount() >= StudentPageTab1Classlist.this.totalItemCount && !isLoading) || isInitialState) {
                        Log.i(TAG, "about to start background task");
                        isLoading = true;
                        isInitialState = false;
                        currentPage = classlist.size() / visibleThreshold;
                        currentPage = (currentPage <= 1) ? 1 : currentPage;
                        Log.i(TAG, "currentPage == "+currentPage);
                        new ClassesResultBackgroundTask().execute(
                                currentPage,
                                //"dev@dev.com" //email
                                5,
                                //student_id
                                7502 // TODO: change for real student
                        );

                    }

                }
            });


            SharedPreferences sharedPref = getContext().getSharedPreferences(
                    getString(R.string.preference_file_key), Context.MODE_PRIVATE);

            email = sharedPref.getString(getString(R.string.USER_EMAIL), "none");
            //student_id = sharedPref.getString(getString(R.string.USER_ID), "none");
            Log.i(TAG, "finished onCreateView in StudentPageTab1Classlist");
        }
        catch(NullPointerException e){
            Log.e(TAG, e.getClass().toString() + e.getMessage());
        }
    }



    private class MyAdapter extends BaseAdapter{

        private Context context;
        //private ArrayList<ClassResult> classlist;
/*        private ArrayList<ClassResult> list;

        private ILoadmore loadmore;*/

        public MyAdapter (Context context){//, ArrayList<String[]> list){
            this.context = context;
            //this.list = list;
        }

/*        public void addListItemToAdapter(ArrayList<ClassResult> list){
            classlist.addAll(list);
            this.notifyDataSetChanged();
        }*/

        @Override
        public int getCount() {
            return classlistTemp.length;
        }

        @Override
        public Object getItem(int i) {
            return null;
        }

        @Override
        public long getItemId(int i) {
            return 0;
        }

        @SuppressLint({"ViewHolder", "InflateParams"})
        @Override
        public View getView(int i, View view, ViewGroup viewGroup) {
            //View v = View.inflate(context, R.layout.listview_student_page_classlist, null);
            view = getLayoutInflater().inflate(R.layout.listview_student_page_classlist, null);

            TextView class_name = (TextView) view.findViewById(R.id.classNameDisplay);
            TextView teacher_name = (TextView) view.findViewById(R.id.teacherNameDisplay);
            //TextView class_description = (TextView) view.findViewById(R.id.classDescriptionDisplay);

            /*class_name.setText(classlistTemp[i]);
            teacher_name.setText(classteacherlistTemp[i]);
            class_description.setText(classdesclistTemp[i]);*/

            try {
//                Log.i(TAG, "class_name = "+classlist.get(i).getCourseDescription());
//                Log.i(TAG, "teacher_name = " +classlist.get(i).getCourseInstructor());
//                class_name.setText(classlist.get(i).getCourseInstructor());
//                teacher_name.setText(classlist.get(i).getCourseDescription());
                String course = classlist.get(i).getTitle();
                String teacher = classlist.get(i).getFirstName() + " " + classlist.get(i).getLastName();

                Log.i(TAG, "class_name = " + course);
                Log.i(TAG, "teacher_name = " + teacher);
                class_name.setText(course);
                teacher_name.setText(teacher);
            }
            catch(IndexOutOfBoundsException e){
                Log.e(TAG, e.getMessage());
            }
            return view;
        }

    }






    @SuppressLint("StaticFieldLeak")
    class ClassesResultBackgroundTask extends AsyncTask<Integer, Void, ClassListResult<ClassResult>>{

        private static final String TAG = "ClassesResultTask";

        Response<ClassListResult<ClassResult>> response;
        WolfpackClient client;

        @Override
        protected void onPreExecute(){
            mListViewClasses.addFooterView(footView);
        }

        @Override
        protected ClassListResult<ClassResult> doInBackground(Integer... params) {

            try{
                Log.i(TAG, "starting task");
                client = StudentPage.getWolfpackClientInstance();
                Log.i(TAG, "configuring params");

                Log.i(TAG, "currentPage = "+ params[0] +
                        "\nrowsPerPage = " + params[1] +
                        "\nstudent_id = " + params[2]);

                Call<ClassListResult<ClassResult>> call = client.findEnrolledClasses(
                        params[0],
                        params[1],
                        params[2],
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
            if (result != null && result.getSuccess() != 0) {
                Log.i(TAG, result.toString());
                //update data adapter and UI
                mListViewClasses.setVisibility(View.VISIBLE);
                classlist.addAll((ArrayList<ClassResult>) result.getResults());
                adapter.notifyDataSetChanged();
                adapter.notifyDataSetChanged();

                totalItemCount = Integer.parseInt(result.getTotalResults());

                //remove loading view after update listview
                mListViewClasses.removeFooterView(footView);
                isLoading = false;
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