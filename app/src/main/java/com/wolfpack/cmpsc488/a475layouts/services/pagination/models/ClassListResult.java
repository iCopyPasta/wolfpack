
package com.wolfpack.cmpsc488.a475layouts.services.pagination.models;

import java.util.List;
import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;


public class ClassListResult<T> {

    @SerializedName("totalPages")
    @Expose
    private Integer totalPages;
    @SerializedName("totalResults")
    @Expose
    private String totalResults;
    @SerializedName("success")
    @Expose
    private Integer success;
    @SerializedName("results")
    @Expose
    private List<T> results = null;

    public Integer getTotalPages() {
        return totalPages;
    }

    public void setTotalPages(Integer totalPages) {
        this.totalPages = totalPages;
    }

    public String getTotalResults() {
        return totalResults;
    }

    public void setTotalResults(String totalResults) {
        this.totalResults = totalResults;
    }

    public Integer getSuccess() {
        return success;
    }

    public void setSuccess(Integer success) {
        this.success = success;
    }

    public List<T> getResults() {
        return results;
    }

    public void setResults(List<T> results) {
        this.results = results;
    }


    @Override
    public String toString(){
        String retVal =  "totalResults: " + getTotalResults() +
                ", totalPages: " + getTotalPages() + "\n";

        StringBuilder stringBuilder = new StringBuilder(retVal);
        if(results != null){
            int i = 0;
            for(T el: results){
                stringBuilder.append("(");
                stringBuilder.append((i++));
                stringBuilder.append("): ");
                stringBuilder.append(el);
                stringBuilder.append("\n");
            }
        }
        return stringBuilder.toString();
    }
}