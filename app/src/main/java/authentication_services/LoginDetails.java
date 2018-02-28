package authentication_services;

import com.google.gson.annotations.SerializedName;

/**
 * Created by pablo on 2/19/18.
 */

public class LoginDetails {

    @SerializedName("success")
    private int status;
    @SerializedName("message")
    private String message;

    LoginDetails(int status, String message){
        this.status = status;
        this.message = message;
    }

    LoginDetails(){

    }

    public int getStatus() {
        return status;
    }

    public void setStatus(int status) {
        this.status = status;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    @Override
    public String toString(){
        return "JSON OBJ: message = " + this.getMessage()
                + " status = " + this.getStatus();
    }

    /*private String itemName;
    private double price;

    public String getItemName() {
        return itemName;
    }

    public void setItemName(String itemName) {
        this.itemName = itemName;
    }

    public double getPrice() {
        return price;
    }

    public void setPrice(double price) {
        this.price = price;
    }

    @Override
    public String toString() {
        NumberFormat numberFormat = NumberFormat.getCurrencyInstance();
        return itemName + " (" + numberFormat.format(price) + ")";
    }*/

}
