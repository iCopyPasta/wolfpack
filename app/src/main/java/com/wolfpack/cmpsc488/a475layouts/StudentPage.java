package com.wolfpack.cmpsc488.a475layouts;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;

public class StudentPage extends AppCompatActivity {

    public static final String TAG = "StudentPage";

    private ListView listView;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_student_page);



        //populate list view
        /* TODO: Use database to find classes that the student is enrolled
            Currently it is displaying a hard coded list for demonstrating purposes
            */
        listView = (ListView) findViewById(R.id.classListView);

        ArrayAdapter<String> mAdapter = new ArrayAdapter<String>(getApplicationContext(),
                android.R.layout.simple_list_item_1,
                getResources().getStringArray(R.array.classesEnrolled));


        listView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> adapterView, View view, int i, long l){
                Intent intent = new Intent(StudentPage.this, SessionPage.class);
                intent.putExtra("ClassName", listView.getItemAtPosition(i).toString());
                Log.i(TAG, "hello from onItemClick");

                startActivity(intent);
            }
        });

        listView.setAdapter(mAdapter);

    }


}
