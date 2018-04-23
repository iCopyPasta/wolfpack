package com.wolfpack.cmpsc488.a475layouts.experiences.student;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.DialogFragment;
import android.content.DialogInterface;
import android.os.Bundle;
import android.util.Log;

import org.w3c.dom.Text;


public class ActiveSessionDialog extends DialogFragment {

    private static final String TAG = "ActiveSessionJAVA";

    public static Bundle getInfo() {
        return information;
    }

    public static void setInfo(Bundle info) {
        information = info;
    }

    private static Bundle information;

    public interface ActiveSessionDialogListener{
        void onPositiveClick(Bundle info);
        void onNegativeClick();
    }

    private static ActiveSessionDialog activeSessionDialog;
    private ActiveSessionDialogListener mListener;
    
    public static ActiveSessionDialog newInstance(){
        if(activeSessionDialog == null){
            activeSessionDialog = new ActiveSessionDialog();
        }

        return activeSessionDialog;
    }

    @Override
    public void onResume(){
        super.onResume();
        Log.i(TAG, "onResume");

        onAttach(getActivity());
    }

    @Override
    public void onPause() {
        super.onPause();
    }

    @Override
    public Dialog onCreateDialog(Bundle savedInstanceState){

        AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());

        builder.setTitle("Join current polling session")
                .setMessage("There is a current session running for this class")
                .setPositiveButton("Join", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int id) {
                        if(information != null){
                            mListener.onPositiveClick(information);
                        }else{
                            Log.e(TAG, "onClick: INFO WAS NULL");
                        }
                    }
                })
                .setNegativeButton("No", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int id) {
                        mListener.onNegativeClick();
                    }
                })
        .setCancelable(false);
        setCancelable(false);

        //setCanceledOnTouchOutside(false);
        return builder.create();
    }

    @Override
    public void onAttach(Activity activity){
        super.onAttach(activity);

        try{
            mListener = (ActiveSessionDialogListener) activity;
        }
        catch (ClassCastException e){
            System.out.println(e.getMessage());
            throw e;
        }
    }

}
