package com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models;
import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;
/**
 * Created by peo5032 on 3/29/18.
 */

public class ValidateQuestionInfo {
    @SerializedName("question_id")
    @Expose
    private String questionId;
    @SerializedName("question_set_id")
    @Expose
    private String questionSetId;
    @SerializedName("question_history_id")
    @Expose
    private String questionHistoryId;
    @SerializedName("question_session_id")
    @Expose
    private String questionSessionId;

    public String getQuestionId() {
        return questionId;
    }

    public void setQuestionId(String questionId) {
        this.questionId = questionId;
    }

    public String getQuestionSetId() {
        return questionSetId;
    }

    public void setQuestionSetId(String questionSetId) {
        this.questionSetId = questionSetId;
    }

    public String getQuestionHistoryId() {
        return questionHistoryId;
    }

    public void setQuestionHistoryId(String questionHistoryId) {
        this.questionHistoryId = questionHistoryId;
    }

    public String getQuestionSessionId() {
        return questionSessionId;
    }

    public void setQuestionSessionId(String questionSessionId) {
        this.questionSessionId = questionSessionId;
    }

}
