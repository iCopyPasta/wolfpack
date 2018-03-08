package com.wolfpack.cmpsc488.a475layouts.experiences.student;

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

import com.wolfpack.cmpsc488.a475layouts.services.authentication.WolfpackClient;
import pagination.ILoadmore;
import pagination.PaginationAdapter;
import pagination.models.SearchClassResult;
import pagination.models.SearchResultSection;
import retrofit2.Call;
import retrofit2.Response;

import com.wolfpack.cmpsc488.a475layouts.R;

public class StudentPageTab2AddClass extends Fragment {

    ArrayList<SearchResultSection> items = new ArrayList<>();
    PaginationAdapter adapter;
    EditText classIdSearchEditText;
    boolean isBackgroundTaskRunning;

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
        final RecyclerView recyclerView = (RecyclerView) getActivity().findViewById(R.id.sectionResults);
        recyclerView.setLayoutManager(new LinearLayoutManager(getActivity()));
        adapter = new PaginationAdapter(recyclerView, getActivity(), items);
        recyclerView.setAdapter(adapter);
        recyclerView.setNestedScrollingEnabled(true);
        isBackgroundTaskRunning = false;


        adapter.setLoadmore(
                new ILoadmore() {
                    @Override
                    public void onLoadMore() {

                        if(isBackgroundTaskRunning){
                            Log.i("adapter:onLoadMore", "we avoided multiple requests!");
                        } else{
                            isBackgroundTaskRunning = true;
                            new ResultBackgroundTask().execute(
                                    classIdSearchEditText.getText().toString());
                        }



                    }
                }
        );

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
                                    classIdSearchEditText.setEnabled(false);

                                    if(adapter.getItemCount() == 0){
                                        Log.i("onKey", "adapter has no items");
                                    }

                                    if(isBackgroundTaskRunning){
                                        Log.i("adapter:onLoadMore", "we avoided multiple requests!");
                                    } else{
                                        isBackgroundTaskRunning = true;
                                        new ResultBackgroundTask().execute(
                                                classIdSearchEditText.getText().toString());
                                    }


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

    class ResultBackgroundTask extends AsyncTask<String, Void, SearchClassResult<SearchResultSection>> {

        private static final String TAG = "ResultBackgroundTask";

        Response<SearchClassResult<SearchResultSection>> response;
        WolfpackClient client;

        @Override
        protected SearchClassResult<SearchResultSection> doInBackground(String... params) {

            try {
                Log.i(TAG, "About to try network request out");

                client = StudentPage.getWolfpackClientInstance();

                Log.i(TAG, "setting call with parameters");

                Call<SearchClassResult<SearchResultSection>> call = client.findClasses(
                        adapter.getLastPageNumber(),
                        params[0],
                        "findClasses");

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

            } catch (Exception e){
                Log.e(TAG, e.getClass().toString() + e.getMessage());
                return null;
            }
        }

        @Override
        protected void onPostExecute(final SearchClassResult<SearchResultSection> result) {

            //we successfully retrieved a valid value back from server
            if(result != null){
                Log.i(TAG, "result was not null");

                items.addAll(result.getDetailedObjects());
                adapter.notifyItemInserted(items.size() - 1);
                adapter.notifyDataSetChanged();

                Log.i(TAG, "result: " + result.toString());
                Log.i(TAG, "detailedObjects: " + (result.getDetailedObjects() == null));

                classIdSearchEditText.setEnabled(true);
                isBackgroundTaskRunning = false;
                adapter.setLoaded();
                Log.i(TAG, "we made it all the way to onPostExecute");
            }
            else{
                classIdSearchEditText.setEnabled(true);
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
