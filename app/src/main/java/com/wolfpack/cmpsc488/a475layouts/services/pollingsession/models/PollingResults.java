package com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models;

import java.util.List;
import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

/**
 * Created by peo5032 on 3/24/18.
 */

public class PollingResults<T> {
    @SerializedName("results")
    @Expose
    private List<T> results = null;

    public List<T> getResults() {
        return results;
    }

    public void setResults(List<T> results) {
        this.results = results;
    }
}
