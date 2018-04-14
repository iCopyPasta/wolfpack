package com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models;

/**
 * Created by peo5032 on 4/10/18.
 */


import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;
import java.util.ArrayList;

public class ActiveCombinationResults {

    @SerializedName("activeSessionResults")
    @Expose
    private ArrayList<ActiveSessionInfo> activeSessionResults = null;
    @SerializedName("activeQuestionResults")
    @Expose
    private ArrayList<ActiveQuestionInfo> activeQuestionResults = null;

    public ArrayList<ActiveSessionInfo> getActiveSessionResults() {
        return activeSessionResults;
    }

    public void setActiveSessionResults(ArrayList<ActiveSessionInfo> activeSessionResults) {
        this.activeSessionResults = activeSessionResults;
    }

    public ArrayList<ActiveQuestionInfo> getActiveQuestionResults() {
        return activeQuestionResults;
    }

    public void setActiveQuestionResults(ArrayList<ActiveQuestionInfo> activeQuestionResults) {
        this.activeQuestionResults = activeQuestionResults;
    }
}