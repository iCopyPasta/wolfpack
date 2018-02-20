package com.wolfpack.cmpsc488.a475layouts;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.DialogFragment;
import android.content.DialogInterface;
import android.os.Bundle;


public class ActiveSessionDialog extends DialogFragment {

    public interface ActiveSessionDialogListener{
        void onPositiveClick();
        void onNegativeClick();
    }

    ActiveSessionDialogListener mListener;

    @Override
    public Dialog onCreateDialog(Bundle savedInstanceState){

        AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());

        builder.setTitle("Join current polling session")
                .setMessage("There is a current session running for this class")
                .setPositiveButton("Join", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int id) {
                        mListener.onPositiveClick();
                    }
                })
                .setNegativeButton("No", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int id) {
                        mListener.onNegativeClick();
                    }
                });

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
